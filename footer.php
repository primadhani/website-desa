<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .footer {
            background: #5a1212;
            color: white;
            padding: 25px 20px 15px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin-top: auto;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            align-items: start;
        }

        .footer-section h3 {
            font-size: 1.4rem;
            margin-bottom: 10px;
            color: #fff;
            font-weight: 600;
        }

        .footer-section p {
            line-height: 1.3;
            margin-bottom: 4px;
            color: #f0f0f0;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .contact-item i {
            width: 20px;
            text-align: center;
            color: #fff;
        }

        .contact-item a {
            color: #f0f0f0;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contact-item a:hover {
            color: #fff;
            text-decoration: underline;
        }

        .copyright {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            color: #e0e0e0;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .footer-container {
                grid-template-columns: 1fr;
                gap: 20px;
                text-align: center;
            }

            .footer {
                padding: 20px 15px 12px;
            }

            .footer-section h3 {
                font-size: 1.2rem;
            }

            .contact-item {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .footer {
                padding: 15px 10px 10px;
            }

            .footer-section h3 {
                font-size: 1.1rem;
                margin-bottom: 8px;
            }

            .footer-section p {
                font-size: 0.9rem;
            }

            .copyright {
                font-size: 0.8rem;
                margin-top: 15px;
                padding-top: 12px;
            }

            h1 {
                font-size: 24px;
                line-height: 1.2;
            }
            h2 {
                font-size: 22px;
                line-height: 1.2;
            }
            h3 {
                font-size: 20px;
                line-height: 1.3;
            }
            h4 {
                font-size: 18px;
                line-height: 1.4;
            }
            h5 {
                font-size: 16px;
                line-height: 1.4;
            }
            h6 {
                font-size: 14px;
                line-height: 1.5;
            }
            p {
                font-size: 14px;
                line-height: 1.5;
            }
        }
    </style>
</head>
<body>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3>Pemerintah Desa Selorejo</h3>
            <p>Jl. Raya Caruban-Nganjuk</p>
            <p>Desa Selorejo, Kecamatan Bagor</p>
            <p>Kabupaten Nganjuk</p>
            <p>Provinsi Jawa Timur</p>
        </div>

        <div class="footer-section">
            <h3>Hubungi Kami</h3>
            <div class="contact-info">
                <div class="contact-item">
                    <i>📞</i>
                    <a href="tel:081234567890">081234567890</a>
                </div>
                <div class="contact-item">
                    <i>✉️</i>
                    <a href="mailto:example@gmail.com">example@gmail.com</a>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright">
        <p>&copy; <?php echo date('Y'); ?> Powered by KKNT UNESA 2025</p>
    </div>
</footer>

</body>
</html>