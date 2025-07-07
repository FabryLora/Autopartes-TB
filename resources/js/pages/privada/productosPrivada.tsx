import ProductosPrivadaRow from '@/components/productosPrivadaRow';
import Slider from '@/components/slider';
import { Head, router, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import toast from 'react-hot-toast';
import DefaultLayout from '../defaultLayout';

export default function ProductosPrivada({ categorias, subcategorias }) {
    const { productos } = usePage().props;

    const [margenSwitch, setMargenSwitch] = useState(false);

    useEffect(() => {
        localStorage.setItem('margenSwitch', JSON.stringify(margenSwitch));
    }, [margenSwitch]);

    const handlePageChange = (page) => {
        router.get(
            route('index.privada.subproductos'),
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

    return (
        <DefaultLayout>
            <Head>
                <title>Productos</title>
            </Head>

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
                                className="focus:outline-primary-orange col-span-2 bg-white pl-2 transition duration-300 outline-none"
                            />
                            <input
                                name="qty"
                                placeholder="Cantidad"
                                type="number"
                                className="focus:outline-primary-orange col-span-2 bg-white pl-2 transition duration-300 outline-none"
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
                            name="marca"
                            className="focus:outline-primary-orange h-[47px] w-full border bg-white outline-0 transition duration-300 focus:outline"
                        >
                            <option value="">Marca</option>
                            {categorias?.map((categoria) => (
                                <option key={categoria.id} value={categoria.name}>
                                    {categoria.name}
                                </option>
                            ))}
                        </select>

                        <select
                            name="modelo"
                            className="focus:outline-primary-orange h-[47px] w-full border bg-white outline-0 transition duration-300 focus:outline"
                        >
                            <option value="">Modelo</option>
                            {subcategorias?.map((subcategoria) => (
                                <option key={subcategoria.id} value={subcategoria.name}>
                                    {subcategoria.name}
                                </option>
                            ))}
                        </select>

                        <select
                            name="medida"
                            className="focus:outline-primary-orange h-[47px] w-full border bg-white outline-0 transition duration-300 focus:outline"
                        >
                            <option value="">Medida</option>
                            {/* {{-- @foreach($medidas as $medida)
            <option value="{{ $medida }}">{{ $medida }}</option>
            @endforeach --}} */}
                        </select>

                        <input
                            type="text"
                            name="code"
                            placeholder="Código"
                            className="focus:outline-primary-orange h-[47px] w-full border bg-white pl-2 outline-0 transition duration-300 focus:outline"
                        />
                        <input
                            type="text"
                            name="code_oem"
                            placeholder="Cód. OEM"
                            className="focus:outline-primary-orange h-[47px] w-full border bg-white pl-2 outline-0 transition duration-300 focus:outline"
                        />
                        <input
                            type="text"
                            name="descripcion"
                            placeholder="Cód. equivalente"
                            className="focus:outline-primary-orange h-[47px] w-full border bg-white pl-2 outline-0 transition duration-300 focus:outline"
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
                    <div className="flex flex-row justify-between">
                        <p className="text-[16px] font-bold">Llevando 10 o más productos y obtené un 5% de descuento</p>
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
                        <div className="grid h-[52px] grid-cols-12 items-center bg-[#F5F5F5]">
                            <p></p>
                            <p>Código</p>
                            <p>Codigo OEM</p>
                            <p>Descripcion</p>
                            <p>Marca</p>
                            <p className="col-span-2">Modelo</p>
                            <p className="">Precio</p>
                            <p className="text-center">Cantidad</p>
                            <p>Subtotal</p>
                            <p className="text-center">Stock</p>
                            <p></p>
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
