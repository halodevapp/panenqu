<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .container {
            border: 1px solid rgba(0, 0, 0, 0.2);
            display: grid;
            margin: 0 auto;
            border-radius: 10px;
            width: 85%;
            overflow: hidden;
            background-color: white;
        }

        .text-center {
            text-align: center;
        }

        .btn {
            border-radius: 5px;
            border: none;
            padding: 5px 10px;
            margin: 10px 0;
            cursor: pointer;
            display: inline-block;
        }

        .btn-danger {
            background-color: rgb(206, 14, 14);
            color: white !important;
            font-weight: bolder;
        }

        .footer {
            margin: 0 auto;
            border-top: 1px solid rgba(0, 0, 0, 0.2);
            padding: 15px;
        }

        .footer p,
        .footer h5 {
            margin: 5px 0 0 0;
            padding: 0;
        }

        .banner {
            width: 100%;
            height: auto
        }

        .footer div {
            display: inline-block;
            vertical-align: top;
            height: inherit;
            margin-right: 25px;
        }

        ul {
            margin: 0;
            padding: 0;
        }

        a {
            text-decoration: none;
        }

        .links-section a {
            display: block;
            margin-bottom: 5px;
        }

        .socmed-section div {
            margin-top: 10px;
            display: block;
        }

        .socmed-section a {
            margin-right: 20px;
        }

        .footer a {
            color: rgb(59, 59, 59);
        }

        .footer h5 {
            margin: 5px 0;
        }

        .box {
            border-radius: 10px;
            padding: 50px 0;
            width: 100%;
            background-color: rgba(192, 223, 224, 0.1);
        }
    </style>
</head>

<body>
    <div class="box">
        <div class="container">
            <img class="banner" src="{{ $message->embed($banner) }}" alt="{{ $article->article_title }}">
            <div style="padding: 0 15px;">
                <a class="article-title" href="{{ env('FE_URL') . "/social/{$article->article_slug}" }}">
                    <h1>{{ $article->article_title }}</h1>
                </a>
                <h3>{{ date('d F Y', strtotime($article->publish_date)) }}</h3>
                {!! $article->article_content !!}

                <div class="footer">
                    <div>
                        <h5>Alamat</h5>
                        {!! $siteConfig['COMPANY_ADDRESS'] !!}
                        <h5>Whatsapp</h5>
                        <p>
                            {{ $siteConfig['WA_NUMBER'] }}
                        </p>
                        <h5>Email</h5>
                        <p>
                            {{ $siteConfig['COMPANY_EMAIL'] }}
                        </p>
                    </div>
                    <div class="links-section">
                        <h5>Links</h5>
                        <div>
                            <a href="{{ env('FE_URL') . '/about' }}">Tentang</a>
                            <a href="{{ env('FE_URL') . '/social' }}">Sosial</a>
                            <a href="{{ env('FE_URL') . '/product' }}">Produk</a>
                            <a href="{{ env('FE_URL') . '/partner' }}">Mitra</a>
                        </div>
                        <div style="padding-left: 20px;">
                            <a href="{{ env('FE_URL') . '/store' }}">Store</a>
                            <a href="{{ env('FE_URL') . '/contact' }}">Kontak</a>
                            <a href="{{ env('FE_URL') . '/event' }}">Event</a>
                        </div>
                    </div>
                    <div class="socmed-section">
                        <h5>Social Media</h5>
                        <a href="https://www.facebook.com/panenqu">Facebook</a>
                        <a href="https://www.instagram.com/panenqu/">Instagram</a>
                        <a href="https://www.youtube.com/@panenqu">Facebook</a>
                        <div>
                            <a href="{{ env('APP_URL') . "/api/unsubscribe/{$email}" }}"
                                class="btn btn-danger">Unsubscribe</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
