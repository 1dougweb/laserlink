<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    /**
     * Display the contact page
     */
    public function index(): View
    {
        $contactInfo = [
            'phone' => Setting::get('site_phone'),
            'whatsapp' => Setting::get('site_whatsapp'),
            'email' => Setting::get('site_email'),
            'address' => Setting::get('site_address'),
            'city' => Setting::get('site_city'),
            'state' => Setting::get('site_state'),
            'zip' => Setting::get('site_zip'),
            'opening_hours' => Setting::get('site_opening_hours', 'Segunda a Sexta: 8h às 18h'),
            'business_hours' => Setting::get('site_opening_hours', 'Segunda a Sexta: 8h às 18h'),
            'map_embed_url' => Setting::get('contact_map_embed_url', ''),
        ];
        
        // Redes Sociais
        $socialMedia = [
            'facebook' => [
                'url' => Setting::get('social_facebook', ''),
                'icon' => 'bi-facebook',
                'color' => 'bg-blue-600 hover:bg-blue-700',
                'name' => 'Facebook'
            ],
            'instagram' => [
                'url' => Setting::get('social_instagram', ''),
                'icon' => 'bi-instagram',
                'color' => 'bg-pink-600 hover:bg-pink-700',
                'name' => 'Instagram'
            ],
            'twitter' => [
                'url' => Setting::get('social_twitter', ''),
                'icon' => 'bi-twitter-x',
                'color' => 'bg-gray-900 hover:bg-black',
                'name' => 'Twitter/X'
            ],
            'linkedin' => [
                'url' => Setting::get('social_linkedin', ''),
                'icon' => 'bi-linkedin',
                'color' => 'bg-blue-700 hover:bg-blue-800',
                'name' => 'LinkedIn'
            ],
            'youtube' => [
                'url' => Setting::get('social_youtube', ''),
                'icon' => 'bi-youtube',
                'color' => 'bg-red-600 hover:bg-red-700',
                'name' => 'YouTube'
            ],
            'tiktok' => [
                'url' => Setting::get('social_tiktok', ''),
                'icon' => 'bi-tiktok',
                'color' => 'bg-gray-900 hover:bg-black',
                'name' => 'TikTok'
            ],
            'pinterest' => [
                'url' => Setting::get('social_pinterest', ''),
                'icon' => 'bi-pinterest',
                'color' => 'bg-red-700 hover:bg-red-800',
                'name' => 'Pinterest'
            ],
        ];
        
        // Filtrar apenas redes sociais configuradas
        $socialMedia = array_filter($socialMedia, function($social) {
            return !empty($social['url']);
        });
        
        // Carregar FAQs
        $faqs = json_decode(Setting::get('contact_faq', '[]'), true);
        if (!is_array($faqs)) {
            $faqs = [];
        }
        
        return view('store.contact', compact('contactInfo', 'socialMedia', 'faqs'));
    }

    /**
     * Handle contact form submission
     */
    public function submit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        try {
            // Send email
            Mail::send('emails.contact', $validated, function ($message) use ($validated) {
                $message->to(config('mail.from.address'))
                    ->subject('Contato do Site: ' . $validated['subject'])
                    ->replyTo($validated['email'], $validated['name']);
            });

            return redirect()
                ->route('contact.index')
                ->with('success', 'Mensagem enviada com sucesso! Entraremos em contato em breve.');
        } catch (\Exception $e) {
            return redirect()
                ->route('contact.index')
                ->with('error', 'Erro ao enviar mensagem. Por favor, tente novamente mais tarde.');
        }
    }
}


