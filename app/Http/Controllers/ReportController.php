<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Página principal de relatórios
     */
    public function index()
    {
        // Estatísticas gerais
        $totalOrders = Order::count();
        $totalRevenue = Order::whereNotIn('status', ['cancelled'])->sum('total_amount');
        $totalProducts = Product::where('is_active', true)->count();
        $totalBudgets = Budget::count();
        
        // Estatísticas do mês atual
        $currentMonth = now()->startOfMonth();
        $monthOrders = Order::where('created_at', '>=', $currentMonth)->count();
        $monthRevenue = Order::where('created_at', '>=', $currentMonth)
            ->whereNotIn('status', ['cancelled'])
            ->sum('total_amount');
        
        // Produtos mais vendidos (top 5)
        $topProducts = OrderItem::select('product_id', 'product_name', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id', 'product_name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();
        
        // Orçamentos por status
        $budgetStats = Budget::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');
        
        return view('admin.reports.index', compact(
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'totalBudgets',
            'monthOrders',
            'monthRevenue',
            'topProducts',
            'budgetStats'
        ));
    }

    /**
     * Relatório de Vendas
     */
    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $status = $request->input('status', 'all');
        
        // Query base
        $query = Order::whereBetween('created_at', [$startDate, $endDate]);
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Estatísticas do período
        $totalOrders = $query->count();
        $totalRevenue = $query->whereNotIn('status', ['cancelled'])->sum('total_amount');
        $averageTicket = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        // Pedidos por status
        $ordersByStatus = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');
        
        // Vendas por dia
        $salesByDay = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotIn('status', ['cancelled'])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Produtos mais vendidos no período
        $topProducts = OrderItem::whereHas('order', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                  ->whereNotIn('status', ['cancelled']);
            })
            ->select('product_id', 'product_name', 
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(total_price) as total_revenue'))
            ->groupBy('product_id', 'product_name')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();
        
        // Lista de pedidos
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->when($status !== 'all', function($q) use ($status) {
                return $q->where('status', $status);
            })
            ->with(['user', 'orderItems'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Estatísticas por mês (últimos 12 meses)
        $monthlySales = Order::where('created_at', '>=', now()->subMonths(12))
            ->whereNotIn('status', ['cancelled'])
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as total_revenue')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        return view('admin.reports.sales', compact(
            'startDate',
            'endDate',
            'status',
            'totalOrders',
            'totalRevenue',
            'averageTicket',
            'ordersByStatus',
            'salesByDay',
            'topProducts',
            'orders',
            'monthlySales'
        ));
    }

    /**
     * Relatório de Produtos
     */
    public function products(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $categoryId = $request->input('category_id', 'all');
        
        // Estatísticas gerais
        $totalProducts = Product::where('is_active', true)->count();
        $totalCategories = Category::where('is_active', true)->count();
        
        // Produtos por categoria
        $productsByCategory = Product::where('is_active', true)
            ->select('category_id', DB::raw('count(*) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->get();
        
        // Produtos mais vendidos
        $bestSellers = OrderItem::whereHas('order', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                  ->whereNotIn('status', ['cancelled']);
            })
            ->select('product_id', 'product_name', 
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(total_price) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_id) as total_orders'))
            ->groupBy('product_id', 'product_name')
            ->orderBy('total_sold', 'desc')
            ->limit(20)
            ->get();
        
        // Produtos sem vendas no período
        $productsWithoutSales = Product::where('is_active', true)
            ->whereNotIn('id', function($query) use ($startDate, $endDate) {
                $query->select('product_id')
                    ->from('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->whereBetween('orders.created_at', [$startDate, $endDate])
                    ->whereNotIn('orders.status', ['cancelled']);
            })
            ->when($categoryId !== 'all', function($q) use ($categoryId) {
                return $q->where('category_id', $categoryId);
            })
            ->with('category')
            ->get();
        
        // Performance por categoria
        $categoryPerformance = Category::where('is_active', true)
            ->withCount(['products' => function($q) {
                $q->where('is_active', true);
            }])
            ->get()
            ->map(function($category) use ($startDate, $endDate) {
                $sales = OrderItem::whereHas('order', function($q) use ($startDate, $endDate) {
                        $q->whereBetween('created_at', [$startDate, $endDate])
                          ->whereNotIn('status', ['cancelled']);
                    })
                    ->whereHas('product', function($q) use ($category) {
                        $q->where('category_id', $category->id);
                    })
                    ->selectRaw('SUM(quantity) as total_quantity, SUM(total_price) as total_revenue')
                    ->first();
                
                $category->total_sold = $sales->total_quantity ?? 0;
                $category->total_revenue = $sales->total_revenue ?? 0;
                
                return $category;
            })
            ->sortByDesc('total_revenue');
        
        // Produtos com estoque baixo (se houver campo de estoque)
        $lowStockProducts = Product::where('is_active', true)
            ->when($categoryId !== 'all', function($q) use ($categoryId) {
                return $q->where('category_id', $categoryId);
            })
            ->with('category')
            ->take(10)
            ->get();
        
        // Todas as categorias para o filtro
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('admin.reports.products', compact(
            'startDate',
            'endDate',
            'categoryId',
            'totalProducts',
            'totalCategories',
            'productsByCategory',
            'bestSellers',
            'productsWithoutSales',
            'categoryPerformance',
            'lowStockProducts',
            'categories'
        ));
    }

    /**
     * Exportar relatório de vendas (CSV)
     */
    public function exportSales(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $status = $request->input('status', 'all');
        
        $query = Order::whereBetween('created_at', [$startDate, $endDate]);
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $orders = $query->with(['user', 'orderItems'])->get();
        
        $filename = "relatorio_vendas_{$startDate}_a_{$endDate}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Cabeçalho
            fputcsv($file, [
                'Número do Pedido',
                'Data',
                'Cliente',
                'Email',
                'Telefone',
                'Status',
                'Total',
                'Produtos'
            ], ';');
            
            // Dados
            foreach ($orders as $order) {
                $products = $order->orderItems->pluck('product_name')->implode(', ');
                
                fputcsv($file, [
                    $order->order_number,
                    $order->created_at->format('d/m/Y H:i'),
                    $order->customer_name,
                    $order->customer_email,
                    $order->customer_phone,
                    $order->status_label,
                    number_format($order->total_amount, 2, ',', '.'),
                    $products
                ], ';');
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar relatório de produtos (CSV)
     */
    public function exportProducts(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        
        $products = OrderItem::whereHas('order', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                  ->whereNotIn('status', ['cancelled']);
            })
            ->select('product_id', 'product_name', 
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(total_price) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_id) as total_orders'))
            ->groupBy('product_id', 'product_name')
            ->orderBy('total_sold', 'desc')
            ->get();
        
        $filename = "relatorio_produtos_{$startDate}_a_{$endDate}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Cabeçalho
            fputcsv($file, [
                'Produto',
                'Quantidade Vendida',
                'Receita Total',
                'Número de Pedidos',
                'Ticket Médio'
            ], ';');
            
            // Dados
            foreach ($products as $product) {
                $averageTicket = $product->total_orders > 0 ? $product->total_revenue / $product->total_orders : 0;
                
                fputcsv($file, [
                    $product->product_name,
                    $product->total_sold,
                    number_format($product->total_revenue, 2, ',', '.'),
                    $product->total_orders,
                    number_format($averageTicket, 2, ',', '.')
                ], ';');
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}

