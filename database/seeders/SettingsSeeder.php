<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Configurações Gerais
        Setting::set('site_name', 'Laser Link', 'string', 'Nome do site');
        Setting::set('site_description', 'Especialistas em Acrílicos, Troféus, Medalhas, Placas e Letreiros', 'string', 'Descrição do site');
        Setting::set('site_email', 'contato@laserlink.com.br', 'string', 'Email de contato');
        Setting::set('site_phone', '(11) 99999-9999', 'string', 'Telefone de contato');

        // Configurações WhatsApp
        Setting::set('whatsapp_number', '5511999999999', 'string', 'Número do WhatsApp');
        Setting::set('whatsapp_message', 'Olá! Gostaria de fazer um pedido.', 'string', 'Mensagem padrão do WhatsApp');
        Setting::set('whatsapp_enabled', true, 'boolean', 'WhatsApp habilitado');

        // Configurações Aparência
        Setting::set('logo_path', null, 'string', 'Caminho da logo');
        Setting::set('sidebar_logo_path', null, 'string', 'Caminho da logo do sidebar do cliente');
        Setting::set('primary_color', '#EE0000', 'string', 'Cor primária');
        Setting::set('secondary_color', '#f8f9fa', 'string', 'Cor secundária');
        Setting::set('accent_color', '#ffc107', 'string', 'Cor de destaque');

        // Configurações SEO
        Setting::set('meta_title', 'Laser Link - Especialistas em Acrílicos, Troféus, Medalhas, Placas e Letreiros', 'string', 'Meta título');
        Setting::set('meta_description', 'Especialistas em Acrílicos, Troféus, Medalhas, Placas e Letreiros. Qualidade e precisão em cada projeto.', 'string', 'Meta descrição');
        Setting::set('meta_keywords', 'acrílicos, troféus, medalhas, placas, letreiros', 'string', 'Meta palavras-chave');
        Setting::set('og_title', '', 'string', 'Open Graph título');
        Setting::set('og_description', '', 'string', 'Open Graph descrição');

        // Configurações Gemini AI
        Setting::set('gemini_api_key', '', 'string', 'Chave da API Gemini');
        Setting::set('gemini_model', 'gemini-2.5-flash', 'string', 'Modelo Gemini');
        Setting::set('gemini_temperature', '0.7', 'string', 'Temperatura Gemini');
        Setting::set('gemini_max_tokens', '1024', 'string', 'Máximo de tokens Gemini');
        Setting::set('gemini_enabled', false, 'boolean', 'Gemini AI habilitado');
        Setting::set('google_analytics', '', 'string', 'Google Analytics ID');
        Setting::set('google_search_console', '', 'string', 'Google Search Console');

        // Configurações de Email/SMTP
        Setting::set('mail_mailer', 'smtp', 'string', 'Driver de email (smtp, sendmail, log)');
        Setting::set('mail_host', 'smtp.gmail.com', 'string', 'Host SMTP');
        Setting::set('mail_port', '587', 'string', 'Porta SMTP');
        Setting::set('mail_username', '', 'string', 'Usuário SMTP');
        Setting::set('mail_password', '', 'string', 'Senha SMTP');
        Setting::set('mail_encryption', 'tls', 'string', 'Encriptação (tls, ssl)');
        Setting::set('mail_from_address', 'noreply@laserlink.com.br', 'string', 'Email remetente');
        Setting::set('mail_from_name', 'Laser Link', 'string', 'Nome remetente');
        
        // Configurações de Notificações
        Setting::set('notify_new_user', true, 'boolean', 'Notificar criação de novo usuário');
        Setting::set('notify_new_order', true, 'boolean', 'Notificar novo pedido');
        Setting::set('send_welcome_email', true, 'boolean', 'Enviar email de boas-vindas');
        Setting::set('send_order_confirmation', true, 'boolean', 'Enviar confirmação de pedido');
    }
}
