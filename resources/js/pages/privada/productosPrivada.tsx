import ProductosPrivadaRow from '@/components/productosPrivadaRow';
import Slider from '@/components/slider';
import { Head, Link, router, usePage } from '@inertiajs/react';
import { ChevronUp, X } from 'lucide-react';
import { useEffect, useRef, useState } from 'react';
import toast from 'react-hot-toast';
import DefaultLayout from '../defaultLayout';

export default function ProductosPrivada({ categorias, subcategorias }) {
    const { productos, auth, clienteSeleccionado, id, modelo_id, code, code_oem, desc_visible, flash, medida } = usePage().props;
    const user = auth.user;
    const [scrollStates, setScrollStates] = useState({});
    const containerRefs = useRef({});
    useEffect(() => {
        if (flash?.success) toast.success(flash.success);
        if (flash?.error) toast.error(flash.error);
    }, [flash?.success, flash?.error]);

    const [filtros, setFiltros] = useState([
        { name: 'id', filtro: id, valor: categorias.find((cat) => cat.id == id)?.name || 'Marca' },
        { name: 'modelo_id', filtro: modelo_id, valor: subcategorias.find((sub) => sub.id == modelo_id)?.name || 'Modelo' },
        { name: 'code', filtro: code, valor: code },
        { name: 'code_oem', filtro: code_oem, valor: code_oem },
        { name: 'descripcion', filtro: desc_visible, valor: desc_visible },
        { name: 'medida', filtro: medida, valor: medida },
    ]);

    useEffect(() => {
        setFiltros([
            { name: 'id', filtro: id, valor: categorias.find((cat) => cat.id == id)?.name || 'Marca' },
            { name: 'modelo_id', filtro: modelo_id, valor: subcategorias.find((sub) => sub.id == modelo_id)?.name || 'Modelo' },
            { name: 'code', filtro: code, valor: code },
            { name: 'code_oem', filtro: code_oem, valor: code_oem },
            { name: 'descripcion', filtro: desc_visible, valor: desc_visible },
            { name: 'medida', filtro: medida, valor: medida },
        ]);
    }, [id, modelo_id, code, code_oem, desc_visible, medida]);

    const [margenSwitch, setMargenSwitch] = useState(false);
    const [vendedorScreen, setVendedorScreen] = useState(user?.rol == 'vendedor' && clienteSeleccionado == null);
    const [selectedUserId, setSelectedUserId] = useState(null);

    const [marcaSelected, setMarcaSelected] = useState(id ? id : null);

    useEffect(() => {
        if (user?.rol === 'vendedor' && !clienteSeleccionado) {
            setVendedorScreen(true);
        } else {
            setVendedorScreen(false);
        }
    }, [user, clienteSeleccionado]);

    useEffect(() => {
        localStorage.setItem('margenSwitch', JSON.stringify(margenSwitch));
    }, [margenSwitch]);

    const handlePageChange = (url) => {
        const secureUrl = url.replace('http://', 'https://');
        router.get(
            secureUrl,
            {},
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    };

    const handleFastBuy = (e) => {
        e.preventDefault();

        router.post(
            route('compraRapida'),
            {
                code: e.target.code.value,
                qty: Number(e.target.qty.value),
            },
            {
                preserveScroll: true,
                onSuccess: () => {
                    // Resetear el formulario
                    formRef.current.reset();

                    // Enfocar el primer input
                    const firstInput = formRef.current.querySelector('input[name="code"]');
                    if (firstInput) {
                        firstInput.focus();
                    }
                },
            },
        );
    };

    const seleccionarCliente = (e) => {
        e.preventDefault();

        router.post(
            route('seleccionarCliente'),
            { cliente_id: selectedUserId },
            {
                onSuccess: () => {
                    setVendedorScreen(false);
                    toast.success('Cliente seleccionado correctamente');
                },
            },
        );
    };

    const formRef = useRef(null);

    const handleFastBuyKeyDown = (e) => {
        if (e.key !== 'Enter') return;

        e.preventDefault(); // evita el submit automático por Enter

        const form = formRef.current;
        const focusables = Array.from(form.querySelectorAll('input, select, textarea')).filter(
            (el) => !el.disabled && el.type !== 'hidden' && el.tabIndex !== -1 && el.offsetParent !== null, // visibles
        );

        const idx = focusables.indexOf(e.target);

        if (idx > -1 && idx < focusables.length - 1) {
            const next = focusables[idx + 1];
            next.focus();
            // opcional: seleccionar texto del siguiente input
            if (typeof next.select === 'function') next.select();
        } else {
            // último campo → submit real
            form.requestSubmit();
        }
    };

    const [openCategoria, setOpenCategoria] = useState(id ? id : null);

    useEffect(() => {
        if (openCategoria && containerRefs.current[openCategoria]) {
            const container = containerRefs.current[openCategoria];
            setTimeout(() => {
                const contentHeight = container.scrollHeight;
                const needsScroll = contentHeight > 500;

                setScrollStates((prev) => ({
                    ...prev,
                    [openCategoria]: needsScroll,
                }));
            }, 350);
        }
    }, [openCategoria]);

    const toggleCategoria = (catId) => {
        setOpenCategoria(openCategoria === catId ? null : catId);
    };

    const currentQuery = { id, modelo_id, code, code_oem, desc_visible, medida };

    return (
        <DefaultLayout>
            <Head>
                <title>Productos</title>
            </Head>
            {vendedorScreen && (
                <div className="fixed z-[100] flex h-screen w-screen items-center justify-center bg-black/50">
                    <div className="flex h-[218px] w-[476px] items-center justify-center bg-white max-sm:h-auto max-sm:w-[90%] max-sm:p-4">
                        <form onSubmit={seleccionarCliente} className="flex w-[350px] flex-col items-center gap-6 max-sm:w-full">
                            <h2 className="text-[16px] font-semibold max-sm:text-[14px]">Seleccionar cliente</h2>
                            <select
                                onChange={(e) => setSelectedUserId(e.target.value)}
                                className="h-[48px] w-full border px-2 max-sm:h-[40px]"
                                name="cliente_id"
                                id=""
                            >
                                <option value="">Seleccione un cliente</option>
                                {user?.clientes?.map((cliente) => (
                                    <option key={cliente.id} value={cliente.id}>
                                        {cliente.name}
                                    </option>
                                ))}
                            </select>
                            <div className="flex w-full justify-between gap-5 text-[16px] max-sm:gap-3 max-sm:text-[14px]">
                                <button
                                    type="button"
                                    onClick={() => setVendedorScreen(false)}
                                    className="text-primary-orange border-primary-orange hover:bg-primary-orange h-[41px] w-full border transition duration-300 hover:text-white max-sm:h-[36px]"
                                >
                                    Cancelar
                                </button>
                                <button
                                    onClick={seleccionarCliente}
                                    className="bg-primary-orange hover:bg-opacity-80 hover:text-primary-orange hover:border-primary-orange h-[41px] w-full text-white transition duration-300 hover:border hover:bg-transparent max-sm:h-[36px]"
                                >
                                    Confirmar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

            <div className="mb-10 flex flex-col gap-10 max-sm:gap-6">
                <Slider />
                <div className="bg-primary-orange h-[123px] w-full max-sm:h-auto max-sm:py-4">
                    <div className="mx-auto flex h-full w-[1200px] flex-row items-center max-sm:w-full max-sm:flex-col max-sm:gap-4 max-sm:px-4">
                        <p className="w-1/3 text-[24px] text-white max-sm:w-full max-sm:text-center max-sm:text-[20px]">Compra rápida</p>

                        <form
                            ref={formRef}
                            onSubmit={handleFastBuy}
                            onKeyDown={handleFastBuyKeyDown}
                            className="grid h-[47px] w-full grid-cols-5 gap-5 max-sm:h-auto max-sm:grid-cols-1 max-sm:gap-3"
                        >
                            <input
                                name="code"
                                placeholder="Codigo"
                                type="text"
                                autoComplete="off"
                                className="focus:outline-primary-orange col-span-2 bg-white pl-2 transition duration-300 outline-none placeholder:text-black max-sm:col-span-1 max-sm:h-[40px]"
                            />
                            <input
                                name="qty"
                                placeholder="Cantidad"
                                type="number"
                                inputMode="numeric"
                                className="focus:outline-primary-orange col-span-2 bg-white pl-2 transition duration-300 outline-none placeholder:text-black max-sm:col-span-1 max-sm:h-[40px]"
                            />
                            <button
                                type="submit"
                                className="border text-white transition duration-300 hover:bg-white hover:text-black max-sm:h-[40px]"
                            >
                                Añadir
                            </button>
                        </form>
                    </div>
                </div>
                <div className="w-full">
                    <form
                        action={route('index.privada.productos')}
                        method="GET"
                        className="mx-auto flex h-fit w-[1200px] flex-row items-center gap-4 max-sm:w-full max-sm:flex-col max-sm:gap-3 max-sm:px-4"
                    >
                        <select
                            defaultValue={id || ''}
                            name="id"
                            onChange={(e) => setMarcaSelected(e.target.value)}
                            className="focus:outline-primary-orange h-[47px] w-full border bg-white outline-0 transition duration-300 focus:outline max-sm:h-[40px]"
                        >
                            <option value="">Marca</option>
                            {categorias?.map((categoria) => (
                                <option key={categoria.id} value={categoria.id}>
                                    {categoria.name}
                                </option>
                            ))}
                        </select>

                        <select
                            defaultValue={modelo_id}
                            name="modelo_id"
                            className="focus:outline-primary-orange h-[47px] w-full border bg-white outline-0 transition duration-300 focus:outline max-sm:h-[40px]"
                        >
                            <option value="">Modelo</option>
                            {subcategorias
                                ?.filter((subcategoria) => subcategoria.categoria_id == marcaSelected)
                                .map((subcategoria) => (
                                    <option selected={modelo_id == subcategoria.id} key={subcategoria.id} value={subcategoria.id}>
                                        {subcategoria.name}
                                    </option>
                                ))}
                        </select>

                        <input
                            defaultValue={medida || ''}
                            type="text"
                            name="medida"
                            placeholder="Medida"
                            className="focus:outline-primary-orange h-[47px] w-full border bg-white pl-2 outline-0 transition duration-300 placeholder:text-black focus:outline max-sm:h-[40px]"
                        />

                        <input
                            defaultValue={code || ''}
                            type="text"
                            name="code"
                            placeholder="Código"
                            className="focus:outline-primary-orange h-[47px] w-full border bg-white pl-2 outline-0 transition duration-300 placeholder:text-black focus:outline max-sm:h-[40px]"
                        />
                        <input
                            defaultValue={code_oem || ''}
                            type="text"
                            name="code_oem"
                            placeholder="Cód. OEM"
                            className="focus:outline-primary-orange h-[47px] w-full border bg-white pl-2 outline-0 transition duration-300 placeholder:text-black focus:outline max-sm:h-[40px]"
                        />
                        <input
                            defaultValue={desc_visible || ''}
                            type="text"
                            name="descripcion"
                            placeholder="Descripcion"
                            className="focus:outline-primary-orange h-[47px] w-full border bg-white pl-2 outline-0 transition duration-300 placeholder:text-black focus:outline max-sm:h-[40px]"
                        />

                        <button
                            type="submit"
                            className="bg-primary-orange hover:text-primary-orange hover:border-primary-orange h-[47px] w-full border border-white text-white transition duration-300 hover:bg-white max-sm:h-[40px]"
                        >
                            Buscar
                        </button>
                    </form>
                </div>
                <div className="relative mx-auto flex min-h-[1400px] w-[1200px] flex-col gap-2 max-sm:w-full max-sm:px-4">
                    <div className="absolute top-8 -left-60 w-full max-w-[200px]">
                        <div className="flex w-full flex-col">
                            {categorias?.map((cat) => {
                                const isOpen = openCategoria == cat.id;
                                const needsScroll = scrollStates[cat.id] || false;
                                return (
                                    <div key={cat.id} className="flex flex-col">
                                        <div className="flex flex-row items-center border-t py-3">
                                            <Link
                                                href={route('index.privada.productos', { id: cat.id })}
                                                className={`w-1/3 text-sm ${id == cat.id ? 'font-bold' : 'font-medium'}`}
                                            >
                                                {cat.name}
                                            </Link>

                                            <button
                                                type="button"
                                                onClick={() => toggleCategoria(cat.id)}
                                                aria-expanded={isOpen}
                                                className="flex w-2/3 justify-end p-1 transition-transform duration-300"
                                            >
                                                <ChevronUp
                                                    className={`h-4 w-4 transition-transform duration-300 ${isOpen ? 'rotate-0' : 'rotate-180'}`}
                                                />
                                            </button>
                                        </div>

                                        {/* Contenedor animado */}
                                        <div
                                            className={`grid overflow-hidden transition-all duration-300 ease-in-out ${
                                                isOpen ? 'translate-y-0 grid-rows-[1fr] opacity-100' : '-translate-y-1 grid-rows-[0fr] opacity-0'
                                            }`}
                                        >
                                            <div
                                                ref={(el) => (containerRefs.current[cat.id] = el)}
                                                className={`max-h-[500px] min-h-0 ${needsScroll ? 'overflow-y-auto' : 'overflow-y-hidden'}`}
                                            >
                                                {cat.subcategorias && (
                                                    <div className="flex flex-col pl-4">
                                                        {cat.subcategorias.map((subcat) => (
                                                            <Link
                                                                href={route('index.privada.productos', { id: cat.id, modelo_id: subcat.id })}
                                                                key={subcat.id}
                                                                className={`flex flex-row justify-between py-2 ${modelo_id == subcat.id ? 'font-bold' : ''}`}
                                                            >
                                                                <h3 className={`text-sm`}>{subcat.name}</h3>
                                                            </Link>
                                                        ))}
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                    <div className="flex flex-row justify-between">
                        <div className="flex flex-row gap-3">
                            {filtros
                                ?.filter((f) => f.filtro)
                                ?.map((filtro) => {
                                    const nextQuery = Object.fromEntries(
                                        Object.entries(currentQuery).filter(([k, v]) => k !== filtro.name && v != null && v !== ''),
                                    );

                                    return (
                                        <div
                                            key={`${filtro.name}-${filtro.filtro}`}
                                            className="bg-primary-orange flex flex-row items-center gap-1 rounded-sm px-1 py-1 text-white"
                                        >
                                            <p>{filtro.valor}</p>
                                            <Link href={route('index.privada.productos', nextQuery)} preserveScroll preserveState>
                                                <X color="white" size="14px" />
                                            </Link>
                                        </div>
                                    );
                                })}
                            {Object.entries(currentQuery).filter(([k, v]) => v != null).length > 0 && (
                                <Link
                                    href={route('index.privada.productos')}
                                    className="flex flex-row items-center gap-1 rounded-sm bg-red-500 px-2 py-1 text-white"
                                >
                                    Borrar Filtros
                                </Link>
                            )}
                        </div>
                        <div className="flex flex-row items-center gap-2">
                            <p className="text-[16px] max-sm:text-[14px]">Vista mostrador</p>
                            <button
                                onClick={() => setMargenSwitch(!margenSwitch)}
                                className={`relative flex h-[15px] w-[28px] items-center rounded-full border bg-gray-200 ${margenSwitch ? 'bg-primary-orange' : ''} transition duration-300`}
                            >
                                <div
                                    className={`broder-2 absolute left-0 h-3 w-3 rounded-full border bg-white ${margenSwitch ? 'translate-x-4' : ''} shadow-lg transition duration-300`}
                                />
                            </button>
                        </div>
                    </div>
                    <div className="w-full">
                        <div className="grid h-[52px] grid-cols-9 items-center bg-[#F5F5F5] max-sm:hidden max-sm:h-[40px] max-sm:grid-cols-4 max-sm:text-[12px]">
                            <p className="max-sm:hidden"></p>
                            <p>Código</p>
                            <p className="max-sm:hidden">Codigo OEM</p>
                            <p>Descripcion</p>
                            <p className="text-right">Precio</p>
                            <p className="text-right max-sm:hidden">Cantidad</p>
                            <p className="text-right max-sm:hidden">Subtotal</p>
                            <p className="text-center max-sm:hidden">Stock</p>
                            <p className="max-sm:hidden"></p>
                        </div>
                        {productos?.data?.map((producto, index) => (
                            <ProductosPrivadaRow
                                key={producto?.id}
                                producto={producto}
                                margenSwitch={margenSwitch}
                                margen={localStorage.getItem('margen') || 0}
                            />
                        ))}
                    </div>
                    <div className="mt-4 flex justify-center">
                        {productos.links && (
                            <div className="flex items-center max-sm:flex-wrap max-sm:gap-1">
                                {productos.links.map((link, index) => (
                                    <button
                                        key={index}
                                        onClick={() => link.url && handlePageChange(link.url)}
                                        disabled={!link.url}
                                        className={`px-4 py-2 max-sm:px-2 max-sm:py-1 max-sm:text-[12px] ${
                                            link.active
                                                ? 'bg-primary-orange text-white'
                                                : link.url
                                                  ? 'bg-gray-300 text-black'
                                                  : 'bg-gray-200 text-gray-500 opacity-50'
                                        } ${index === 0 ? 'rounded-l-md' : ''} ${index === productos.links.length - 1 ? 'rounded-r-md' : ''}`}
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                ))}
                            </div>
                        )}
                    </div>

                    {/* Información de paginación */}
                    <div className="mt-2 text-center text-sm text-gray-600 max-sm:text-[12px]">
                        Mostrando {productos.from || 0} a {productos.to || 0} de {productos.total} resultados
                    </div>
                </div>
            </div>
        </DefaultLayout>
    );
}
