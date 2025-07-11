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
                    <div className="flex h-[218px] w-[476px] items-center justify-center bg-white">
                        <form onSubmit={seleccionarCliente} className="flex w-[350px] flex-col items-center gap-6">
                            <h2 className="text-[16px] font-semibold">Seleccionar cliente</h2>
                            <select
                                onChange={(e) => setSelectedUserId(e.target.value)}
                                className="h-[48px] w-full border px-2"
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
                            <div className="flex w-full justify-between gap-5 text-[16px]">
                                <button
                                    type="button"
                                    onClick={() => setVendedorScreen(false)}
                                    className="text-primary-orange border-primary-orange hover:bg-primary-orange h-[41px] w-full border transition duration-300 hover:text-white"
                                >
                                    Cancelar
                                </button>
                                <button
                                    onClick={seleccionarCliente}
                                    className="bg-primary-orange hover:bg-opacity-80 hover:text-primary-orange hover:border-primary-orange h-[41px] w-full text-white transition duration-300 hover:border hover:bg-transparent"
                                >
                                    Confirmar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

            <div className="mb-10 flex flex-col gap-10">
                <Slider />
                <div className="bg-primary-orange h-[123px] w-full">
                    <div className="mx-auto flex h-full w-[1200px] flex-row items-center">
                        <p className="w-1/3 text-[24px] text-white">Compra rápida</p>
                        <form onSubmit={handleFastBuy} className="grid h-[47px] w-full grid-cols-5 gap-5">
                            <input
                                name="code"
                                placeholder="Codigo"
                                type="text"
                                className="focus:outline-primary-orange col-span-2 bg-white pl-2 transition duration-300 outline-none placeholder:text-black"
                            />
                            <input
                                name="qty"
                                placeholder="Cantidad"
                                type="number"
                                className="focus:outline-primary-orange col-span-2 bg-white pl-2 transition duration-300 outline-none placeholder:text-black"
                            />
                            <button className="border text-white transition duration-300 hover:bg-white hover:text-black">Añadir</button>
                        </form>
                    </div>
                </div>
                <div className="w-full">
                    <form
                        action={route('index.privada.productos')}
                        method="GET"
                        className="mx-auto flex h-fit w-[1200px] flex-row items-center gap-4"
                    >
                        <select
                            defaultValue={id || ''}
                            name="id"
                            className="focus:outline-primary-orange h-[47px] w-full border bg-white outline-0 transition duration-300 focus:outline"
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
                            className="focus:outline-primary-orange h-[47px] w-full border bg-white outline-0 transition duration-300 focus:outline"
                        >
                            <option value="">Modelo</option>
                            {subcategorias?.map((subcategoria) => (
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
                            className="focus:outline-primary-orange h-[47px] w-full border bg-white pl-2 outline-0 transition duration-300 placeholder:text-black focus:outline"
                        />

                        <input
                            defaultValue={code || ''}
                            type="text"
                            name="code"
                            placeholder="Código"
                            className="focus:outline-primary-orange h-[47px] w-full border bg-white pl-2 outline-0 transition duration-300 placeholder:text-black focus:outline"
                        />
                        <input
                            defaultValue={code_oem || ''}
                            type="text"
                            name="code_oem"
                            placeholder="Cód. OEM"
                            className="focus:outline-primary-orange h-[47px] w-full border bg-white pl-2 outline-0 transition duration-300 placeholder:text-black focus:outline"
                        />
                        <input
                            defaultValue={desc_visible || ''}
                            type="text"
                            name="descripcion"
                            placeholder="Descripcion"
                            className="focus:outline-primary-orange h-[47px] w-full border bg-white pl-2 outline-0 transition duration-300 placeholder:text-black focus:outline"
                        />

                        <button
                            type="submit"
                            className="bg-primary-orange hover:text-primary-orange hover:border-primary-orange h-[47px] w-full border border-white text-white transition duration-300 hover:bg-white"
                        >
                            Buscar
                        </button>
                    </form>
                </div>
                <div className="mx-auto flex w-[1200px] flex-col gap-2">
                    <div className="flex flex-row justify-end">
                        <div className="flex flex-row items-center gap-2">
                            <p className="text-[16px]">Vista mostrador</p>
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
                        <div className="grid h-[52px] grid-cols-9 items-center bg-[#F5F5F5]">
                            <p></p>
                            <p>Código</p>
                            <p>Codigo OEM</p>
                            <p>Descripcion</p>
                            <p className="text-right">Precio</p>
                            <p className="text-right">Cantidad</p>
                            <p className="text-right">Subtotal</p>
                            <p className="text-center">Stock</p>
                            <p className=""></p>
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
                            <div className="flex items-center">
                                {productos.links.map((link, index) => (
                                    <button
                                        key={index}
                                        onClick={() => link.url && handlePageChange(link.url.split('page=')[1])}
                                        disabled={!link.url}
                                        className={`px-4 py-2 ${
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
                    <div className="mt-2 text-center text-sm text-gray-600">
                        Mostrando {productos.from || 0} a {productos.to || 0} de {productos.total} resultados
                    </div>
                </div>
            </div>
        </DefaultLayout>
    );
}
