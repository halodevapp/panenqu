<aside class="main-sidebar sidebar-light-primary elevation-1">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="/images/panenqu_sm.png" alt="Panenqu Logo" class="brand-image img-circle">
        <span class="brand-text" style="color: #019549"><strong>PanenQu</strong></span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-collapse-hide-child" data-widget="treeview"
                role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link exact">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @canany(['USER_VIEW', 'PERMISSION_VIEW', 'ROLE_VIEW'])
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user-lock"></i>
                            <p>
                                Authentication
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('USER_VIEW')
                                <li class="nav-item">
                                    <a href="{{ route('user.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Master User</p>
                                    </a>
                                </li>
                            @endcan
                            @can('ROLE_VIEW')
                                <li class="nav-item">
                                    <a href="{{ route('role.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Role</p>
                                    </a>
                                </li>
                            @endcan
                            @can('PERMISSION_VIEW')
                                <li class="nav-item">
                                    <a href="{{ route('permission.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Permission</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @canany(['PRODUCT_VIEW', 'PRODUCT_CATEGORY_VIEW'])
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-fish"></i>
                            <p>Product
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('PRODUCT_VIEW')
                                <li class="nav-item">
                                    <a href="{{ route('product.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Product</p>
                                    </a>
                                </li>
                            @endcan
                            @can('PRODUCT_CATEGORY_VIEW')
                                <li class="nav-item">
                                    <a href="{{ route('product-category.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Product Category</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @canany(['ARTICLE_VIEW', 'ARTICLE_CATEGORY_VIEW'])
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-newspaper"></i>
                            <p>Article
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('ARTICLE_VIEW')
                                <li class="nav-item">
                                    <a href="{{ route('article.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Article</p>
                                    </a>
                                </li>
                            @endcan
                            @can('ARTICLE_CATEGORY_VIEW')
                                <li class="nav-item">
                                    <a href="{{ route('article-category.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Article Category</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @canany(['MITRA_FAQ_VIEW', 'MITRA_FORM_VIEW'])
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-handshake"></i>
                            <p>Kemitraan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('MITRA_FAQ_VIEW')
                                <li class="nav-item">
                                    <a href="{{ route('mitra-faq.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>FAQ</p>
                                    </a>
                                </li>
                            @endcan
                            @can('MITRA_FORM_VIEW')
                                <li class="nav-item">
                                    <a href="{{ route('mitra-form.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Formulir Kemitraan</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @canany(['CUSTOMER_VIEW', 'CUSTOMER_CATEGORY_VIEW'])
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-people-arrows"></i>
                            <p>Customer
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('CUSTOMER_VIEW')
                                <li class="nav-item">
                                    <a href="{{ route('customer.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Customer</p>
                                    </a>
                                </li>
                            @endcan
                            @can('CUSTOMER_CATEGORY_VIEW')
                                <li class="nav-item">
                                    <a href="{{ route('customer-category.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Customer Category</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @can(['STORE_VIEW'])
                    <li class="nav-item">
                        <a href="{{ route('store.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-store"></i>
                            <p>Store</p>
                        </a>
                    </li>
                @endcan
                @can(['SUBSCRIBER_VIEW'])
                    <li class="nav-item">
                        <a href="{{ route('subscriber.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-thumbs-up"></i>
                            <p>Subscriber</p>
                        </a>
                    </li>
                @endcan
                @can(['TESTIMONI_VIEW'])
                    <li class="nav-item">
                        <a href="{{ route('testimoni.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-star"></i>
                            <p>Testimoni</p>
                        </a>
                    </li>
                @endcan
                @canany(['EVENT_GALERY'])
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-calendar-check"></i>
                            <p>Event
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can(['EVENT_GALERY'])
                                <li class="nav-item">
                                    <a href="{{ route('event-galery.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Galery</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @canany(['CONTACT_CATEGORY_VIEW', 'CONTACT_VIEW'])
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-address-card"></i>
                            <p>Contact Form
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can(['CONTACT_CATEGORY_VIEW'])
                                <li class="nav-item">
                                    <a href="{{ route('contact-category.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Category</p>
                                    </a>
                                </li>
                            @endcan
                            @can(['CONTACT_VIEW'])
                                <li class="nav-item">
                                    <a href="{{ route('contact-form.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Form</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @canany(['WHATSAPP_CONFIG', 'COMPANY_CONFIG', 'NOTIF_VIEW', 'INSTAGRAM_CONFIG', 'COMPANY_CONFIG',
                    'PAGE_CONFIG', 'POPUP_CONFIG'])
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>Site Config
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('NOTIF_VIEW')
                                <li class="nav-item">
                                    <a href="{{ route('config.notification.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Notification Header</p>
                                    </a>
                                </li>
                            @endcan
                            @can('WHATSAPP_CONFIG')
                                <li class="nav-item">
                                    <a href="{{ route('config.whatsapp.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Whatsapp</p>
                                    </a>
                                </li>
                            @endcan
                            @can('COMPANY_CONFIG')
                                <li class="nav-item">
                                    <a href="{{ route('config.company.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Company</p>
                                    </a>
                                </li>
                            @endcan
                            @can('INSTAGRAM_CONFIG')
                                <li class="nav-item">
                                    <a href="{{ route('config.instagram.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Instagram API</p>
                                    </a>
                                </li>
                            @endcan
                            @can('PAGE_CONFIG')
                                <li class="nav-item">
                                    <a href="{{ route('config.page.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Page Config</p>
                                    </a>
                                </li>
                            @endcan
                            @can('POPUP_CONFIG')
                                <li class="nav-item">
                                    <a href="{{ route('config.popup.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>POP UP</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
            </ul>
        </nav>
    </div>
</aside>
