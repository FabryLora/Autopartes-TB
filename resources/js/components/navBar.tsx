import { Link, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';

const Navbar = () => {
    const [scrolled, setScrolled] = useState(false);
    const [searchOpen, setSearchOpen] = useState(false);
    const [showLogin, setShowLogin] = useState(false);

    const { logos, contacto, auth, carrito } = usePage().props;

    useEffect(() => {
        // Check if it's home page or not
        const location = window.location.pathname;
        const isHome = location === '/';

        if (isHome) {
            window.addEventListener('scroll', () => {
                setScrolled(window.scrollY > 0);
            });
        } else {
            setScrolled(true);
        }

        return () => {
            window.removeEventListener('scroll', () => {
                setScrolled(window.scrollY > 0);
            });
        };
    }, []);

    const defaultLinks = [
        { title: 'Empresa', href: '/empresa' },
        { title: 'Productos', href: '/productos' },
        { title: 'Calidad', href: '/calidad' },
        { title: 'Lanzamientos', href: '/lanzamientos' },
        { title: 'Contacto', href: '/contacto' },
    ];

    const privateLinks = [
        { title: 'Productos', href: '/privada/productos' },
        { title: 'Márgenes', href: '/privada/margenes' },
        { title: 'Pedidos', href: '/privada/pedidos' },
        { title: 'Información de pagos', href: '/privada/informacion-de-pagos' },
        { title: 'Cuenta corriente', href: '/privada/cuenta-corriente' },
        { title: 'Lista de precios', href: '/privada/lista-de-precios' },
    ];

    const linksToRender = window.location.pathname.includes('privada') ? privateLinks : defaultLinks;

    return (
        <div
            className={`sticky top-0 z-50 flex h-[131px] w-full flex-col transition-colors duration-300 ${scrolled ? 'bg-white shadow-md' : 'bg-transparent'}`}
        >
            {/* Upper Bar */}
            <div className="bg-primary-orange relative min-h-[49px]">
                <div className="relative mx-auto flex h-full w-[1200px] items-center justify-end gap-6">
                    <div
                        className={`flex items-center gap-2 px-2 py-1 transition-all duration-300 ${searchOpen ? 'w-[200px] border' : 'w-[0px] border-none'}`}
                    >
                        <label className="cursor-pointer" onClick={() => setSearchOpen(!searchOpen)}>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path
                                    d="M16.6 18L10.3 11.7C9.8 12.1 9.225 12.4167 8.575 12.65C7.925 12.8833 7.23333 13 6.5 13C4.68333 13 3.146 12.3707 1.888 11.112C0.63 9.85333 0.000667196 8.316 5.29101e-07 6.5C-0.000666138 4.684 0.628667 3.14667 1.888 1.888C3.14733 0.629333 4.68467 0 6.5 0C8.31533 0 9.853 0.629333 11.113 1.888C12.373 3.14667 13.002 4.684 13 6.5C13 7.23333 12.8833 7.925 12.65 8.575C12.4167 9.225 12.1 9.8 11.7 10.3L18 16.6L16.6 18ZM6.5 11C7.75 11 8.81267 10.5627 9.688 9.688C10.5633 8.81333 11.0007 7.75067 11 6.5C10.9993 5.24933 10.562 4.187 9.688 3.313C8.814 2.439 7.75133 2.00133 6.5 2C5.24867 1.99867 4.18633 2.43633 3.313 3.313C2.43967 4.18967 2.002 5.252 2 6.5C1.998 7.748 2.43567 8.81067 3.313 9.688C4.19033 10.5653 5.25267 11.0027 6.5 11Z"
                                    fill="white"
                                />
                            </svg>
                        </label>
                        <input
                            id="searchInput"
                            type="text"
                            className="w-full border-none bg-transparent text-sm text-white outline-none placeholder:text-white"
                            placeholder="Buscar productos"
                        />
                    </div>

                    <button
                        onClick={() => setShowLogin(!showLogin)}
                        className="z-100 h-[33px] w-[184px] border border-white text-sm text-white uppercase hover:bg-white hover:text-black"
                    >
                        {auth?.user?.name}
                    </button>

                    {showLogin && <div className="fixed inset-0 bg-black/50 transition-all duration-300" />}
                    {showLogin && (
                        <div className="absolute top-12 right-0 flex h-fit w-fit flex-col items-center gap-5 border bg-white p-5 transition-all duration-300">
                            <p>Bienvenido, {auth?.user?.name}!</p>
                            <div className="flex flex-col">
                                <Link
                                    method="post"
                                    href={route('logout')}
                                    className="flex h-[48px] w-[328px] items-center justify-center bg-red-500 font-bold text-white"
                                >
                                    Cerrar sesion
                                </Link>
                            </div>
                        </div>
                    )}
                </div>
            </div>

            {/* Main Navbar Content */}
            <div className="mx-auto flex h-full w-[1200px] items-center justify-between">
                <a href="/">
                    <img src={scrolled ? logos?.logo_secundario : logos?.logo_principal} className="h-10 transition-all duration-300" alt="Logo" />
                </a>

                <div className="hidden items-center gap-6 md:flex">
                    {linksToRender.map((link) => (
                        <Link
                            key={link.href}
                            href={link.href}
                            className={`hover:text-primary-orange text-sm transition-colors duration-300 ${
                                window.location.pathname === link.href ? 'font-bold' : ''
                            }`}
                        >
                            {link.title}
                        </Link>
                    ))}
                    {window.location.pathname.includes('privada') && (
                        <Link href={'/privada/carrito'} className="relative flex items-center gap-2">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="#0072c6"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                className="lucide lucide-shopping-cart-icon lucide-shopping-cart"
                            >
                                <circle cx="8" cy="21" r="1" />
                                <circle cx="19" cy="21" r="1" />
                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                            </svg>
                        </Link>
                    )}
                </div>
            </div>
        </div>
    );
};

export default Navbar;
