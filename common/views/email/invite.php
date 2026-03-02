<?php $this->layout("_theme", ["title" => "Convite de acesso - ".CONF_SITE_NAME]); ?>

<style>
    .email-container {
        max-width: 600px;
        margin: 0 auto;
        background: #ffffff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .header-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 3rem 2rem;
        text-align: center;
        color: white;
    }

    .header-gradient h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
    }

    .header-gradient .logo {
        width: 80px;
        height: 80px;
        background: white;
        border-radius: 20px;
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: #667eea;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }

    .content-section {
        padding: 2.5rem;
        color: #2c3e50;
        line-height: 1.8;
    }

    .content-section h2 {
        color: #2c3e50;
        font-size: 1.5rem;
        margin: 0 0 1rem 0;
        font-weight: 700;
    }

    .content-section p {
        margin: 1rem 0;
        color: #6c757d;
        font-size: 1rem;
    }

    .highlight-box {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 4px solid #667eea;
        padding: 1.5rem;
        margin: 1.5rem 0;
        border-radius: 8px;
    }

    .highlight-box p {
        margin: 0.5rem 0;
        color: #2c3e50;
    }

    .highlight-box strong {
        color: #667eea;
    }

    .button-container {
        text-align: center;
        margin: 2rem 0;
    }

    .btn-primary-cta {
        display: inline-block;
        padding: 1rem 2.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white !important;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
    }

    .btn-primary-cta:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
    }

    .credentials-box {
        background: #fff3cd;
        border: 2px solid #ffeaa7;
        border-radius: 10px;
        padding: 1.5rem;
        margin: 1.5rem 0;
    }

    .credentials-box h3 {
        margin: 0 0 1rem 0;
        color: #856404;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .credentials-box p {
        margin: 0.5rem 0;
        color: #856404;
    }

    .credentials-label {
        font-weight: 700;
        color: #856404;
        margin-right: 0.5rem;
    }

    .credentials-value {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: #2c3e50;
        background: white;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        display: inline-block;
    }

    .security-note {
        background: #e7f3ff;
        border-left: 4px solid #17a2b8;
        padding: 1.5rem;
        margin: 1.5rem 0;
        border-radius: 8px;
    }

    .security-note p {
        margin: 0.5rem 0;
        color: #004085;
        font-size: 0.9rem;
    }

    .security-note strong {
        color: #17a2b8;
    }

    .footer-note {
        text-align: center;
        padding: 1.5rem;
        color: #6c757d;
        font-size: 0.85rem;
        border-top: 1px solid #e9ecef;
    }

    .footer-note p {
        margin: 0.5rem 0;
    }

    @media only screen and (max-width: 600px) {
        .header-gradient {
            padding: 2rem 1rem;
        }

        .header-gradient h1 {
            font-size: 1.5rem;
        }

        .content-section {
            padding: 1.5rem;
        }

        .btn-primary-cta {
            padding: 0.875rem 2rem;
            font-size: 1rem;
        }
    }
</style>

<div class="email-container">
    <div class="header-gradient">
        <div class="logo">P</div>
        <h1><?php echo CONF_SITE_NAME; ?></h1>
        <p style="margin: 0; opacity: 0.9; font-size: 1.1rem;">
            <?php echo CONF_SITE_TITLE; ?>
        </p>
    </div>

    <div class="content-section">
        <h2>Olá <?= $name ?? 'Usuário' ?>, você foi convidado! 🎉</h2>
        
        <p>Você foi convidado para acessar o <strong><?php echo CONF_SITE_NAME; ?></strong>, 
        uma plataforma completa de gerenciamento.</p>


        <div class="button-container">
            <a href="<?= $login_url; ?>" class="btn-primary-cta">
                🚀 ACESSAR
            </a>
        </div>

        <p style="text-align: center; color: #6c757d; font-size: 0.9rem;">
            Clique no botão acima para acessar o painel principal
        </p>

        <div class="security-note">
            <p><strong>🔐 Importante sobre segurança:</strong></p>
            <p>• Este link tem validade limitada por questões de segurança</p>
            <p>• Não compartilhe suas credenciais com terceiros</p>
            <p>• Se você não solicitou este convite, ignore este e-mail</p>
        </div>

        <p style="color: #6c757d; font-size: 0.95rem;">
            Em caso de dúvidas ou problemas ao acessar, entre em contato com o suporte técnico.
        </p>
    </div>

    <div class="footer-note">
        <p><strong>Atenciosamente,</strong></p>
        <p>Equipe <?php echo CONF_SITE_NAME; ?></p>
        <p style="font-size: 0.8rem; margin-top: 1rem;">
            Este é um e-mail automático, por favor não responda.
        </p>
    </div>
</div>
