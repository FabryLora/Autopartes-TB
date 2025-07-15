import ProductosPrivadaRow from '@/components/productosPrivadaRow';
import Slider from '@/components/slider';
import { Head, router, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import toast from 'react-hot-toast';
import DefaultLayout from '../defaultLayout';

export default function ProductosPrivada({ categorias, subcategorias }) {
    const { productos, auth, clienteSeleccionado, id, modelo_id, code, code_oem, desc_visible } = usePage().props;
    const user = auth.user;

    const [margenSwitch, setMargenSwitch] = useState(false);
    const [vendedorScreen, setVendedorScreen] = useState(user?.rol == 'vendedor' && clienteSeleccionado == null);
    const [selectedUserId, setSelectedUserId] = useState(null);

    const [marcaSelected, setMarcaSelected] = useState();

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

    const handlePageChange = (page) => {
        router.get(
            route('index.privada.productos'),
            {
                page: page,
            },
            {
                preserveState: true,
                preserveScroll: true,
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
                    toast.success('Producto añadido al carrito');
                },
                onError: (errors) => {
                    toast.error(errors.message || 'Error al añadir el producto');
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
                            onSubmit={handleFastBuy}
                            className="grid h-[47px] w-full grid-cols-5 gap-5 max-sm:h-auto max-sm:grid-cols-1 max-sm:gap-3"
                        >
                            <input
                                name="code"
                                placeholder="Codigo"
                                type="text"
                                className="focus:outline-primary-orange col-span-2 bg-white pl-2 transition duration-300 outline-none placeholder:text-black max-sm:col-span-1 max-sm:h-[40px]"
                            />
                            <input
                                name="qty"
                                placeholder="Cantidad"
                                type="number"
                                className="focus:outline-primary-orange col-span-2 bg-white pl-2 transition duration-300 outline-none placeholder:text-black max-sm:col-span-1 max-sm:h-[40px]"
                            />
                            <button className="border text-white transition duration-300 hover:bg-white hover:text-black max-sm:h-[40px]">
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
                            defaultValue={modelo_id || ''}
                            name="modelo_id"
                            className="focus:outline-primary-orange h-[47px] w-full border bg-white outline-0 transition duration-300 focus:outline max-sm:h-[40px]"
                        >
                            <option value="">Modelo</option>
                            {subcategorias
                                ?.filter((subcategoria) => subcategoria.categoria_id == marcaSelected)
                                .map((subcategoria) => (
                                    <option key={subcategoria.id} value={subcategoria.id}>
                                        {subcategoria.name}
                                    </option>
                                ))}
                        </select>

                        <input
                            defaultValue={desc_visible || ''}
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
                <div className="mx-auto flex w-[1200px] flex-col gap-2 max-sm:w-full max-sm:px-4">
                    <div className="flex flex-row justify-end max-sm:justify-end">
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
                                        onClick={() => link.url && handlePageChange(link.url.split('page=')[1])}
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
