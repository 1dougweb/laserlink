<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductReviewController extends Controller
{
    /**
     * Store a new review
     */
    public function store(Request $request, Product $product)
    {
        // Log para debug
        \Log::info('Review submission data:', $request->all());
        
        $rules = [
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|min:10|max:5000',
        ];
        
        // Adicionar validação de nome e email apenas se não estiver autenticado
        if (!Auth::check()) {
            $rules['customer_name'] = 'required|string|max:255';
            $rules['customer_email'] = 'required|email|max:255';
        }
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            \Log::error('Review validation failed:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $reviewData = [
            'product_id' => $product->id,
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'is_approved' => true, // Auto-aprovar ou false para moderação
        ];

        if (Auth::check()) {
            $reviewData['user_id'] = Auth::id();
            $reviewData['customer_name'] = Auth::user()->name;
            $reviewData['customer_email'] = Auth::user()->email;
        } else {
            $reviewData['customer_name'] = $request->customer_name;
            $reviewData['customer_email'] = $request->customer_email;
        }

        $review = ProductReview::create($reviewData);

        // Atualizar média de avaliações do produto
        $this->updateProductRating($product);

        return response()->json([
            'success' => true,
            'message' => 'Avaliação enviada com sucesso!',
            'review' => $review->load('user')
        ]);
    }

    /**
     * Mark review as helpful
     */
    public function markHelpful(ProductReview $review)
    {
        $review->increment('helpful_count');

        return response()->json([
            'success' => true,
            'helpful_count' => $review->helpful_count
        ]);
    }

    /**
     * Update product rating average
     */
    private function updateProductRating(Product $product)
    {
        $approvedReviews = $product->approvedReviews;
        
        $product->rating_count = $approvedReviews->count();
        $product->rating_average = $product->rating_count > 0 
            ? round($approvedReviews->avg('rating'), 2) 
            : 0;
        
        $product->save();
    }

    /**
     * Get reviews for a product (with pagination)
     */
    public function index(Product $product, Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $sortBy = $request->get('sort', 'latest'); // latest, helpful, rating

        $query = $product->approvedReviews()->with('user');

        switch ($sortBy) {
            case 'helpful':
                $query->mostHelpful();
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $reviews = $query->paginate($perPage);

        return response()->json($reviews);
    }
}
