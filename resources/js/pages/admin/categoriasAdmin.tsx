import CategoriasAdminRow from '@/components/categoriasAdminRow';
import { router, useForm, usePage } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { useState } from 'react';
import toast from 'react-hot-toast';
import Dashboard from './dashboard';

export default function CategoriasAdmin() {
    const { categorias } = usePage().props;

    const { data, setData, post, reset } = useForm({
        name: '',
    });

    const [searchTerm, setSearchTerm] = useState('');
    const [createView, setCreateView] = useState(false);
    const [subirMarcasYModelos, setSubirMarcasYModelos] = useState(false);
    const [archivo, setArchivo] = useState();

    const handleSubmit = (e) => {
        e.preventDefault();

        post(route('admin.categorias.store'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Marca creada correctamente');
                reset();
                setCreateView(false);
            },
            onError: (errors) => {
                toast.error('Error al crear marca');
                console.log(errors);
            },
        });
    };

    // Manejadores para la paginación del backend
    const handlePageChange = (page) => {
        router.get(
            route('admin.categorias'),
            {
                page: page,
                search: searchTerm,
            },
            {
                preserveState: true,
                preserveScroll: true,
            },
        );
    };

    // Función para realizar la búsqueda
    const handleSearch = () => {
        router.get(
            route('admin.categorias'),
            {
                search: searchTerm,
                page: 1, // Resetear a la primera página al buscar
            },
            {
                preserveState: true,
                preserveScroll: true,
            },
        );
    };

    const importarMarcasYModelos = (e) => {
        e.preventDefault();

        router.post(
            route('importarMarcasYModelos'),
            {
                archivo: archivo,
            },
            {
                onSuccess: () => {
                    toast.success('Marcas y Modelos importados correctamente');
                    reset();
                    setSubirMarcasYModelos(false);
                },
                onError: (errors) => {
                    toast.error('Error al importar Marcas y Modelos');
                    console.log(errors);
                },
            },
        );
    };

    return (
        <Dashboard>
            <div className="flex w-full flex-col p-6">
                <AnimatePresence>
                    {subirMarcasYModelos && (
                        <motion.div
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            exit={{ opacity: 0 }}
                            className="fixed top-0 left-0 z-50 flex h-full w-full items-center justify-center bg-black/50 text-left"
                        >
                            <form onSubmit={importarMarcasYModelos} method="POST" className="relative rounded-lg bg-white text-black">
                                <div className="bg-primary-orange sticky top-0 flex flex-row items-center gap-2 rounded-t-lg p-4">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="28"
                                        height="28"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="#ffffff"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        className="lucide lucide-plus-icon lucide-plus"
                                    >
                                        <path d="M5 12h14" />
                                        <path d="M12 5v14" />
                                    </svg>
                                    <h2 className="text-2xl font-semibold text-white">Subir Ofertas</h2>
                                </div>

                                <div className="max-h-[60vh] w-[500px] overflow-y-auto rounded-md bg-white p-4">
                                    <div className="flex flex-col gap-4">
                                        <label htmlFor="archivo">Archivo</label>
                                        <input
                                            className="file:bg-primary-orange rounded-md p-2 file:cursor-pointer file:rounded-full file:px-4 file:py-2 file:text-white"
                                            type="file"
                                            name="archivo"
                                            id="archivo"
                                            onChange={(e) => setArchivo(e.target.files[0])}
                                        />
                                    </div>
                                </div>
                                <div className="bg-primary-orange sticky bottom-0 flex justify-end gap-4 rounded-b-md p-4">
                                    <button
                                        type="button"
                                        onClick={() => setSubirMarcasYModelos(false)}
                                        className="rounded-md border border-red-500 bg-red-500 px-2 py-1 text-white transition duration-300"
                                    >
                                        Cancelar
                                    </button>
                                    <button className="hover:text-primary-orange rounded-md px-2 py-1 text-white outline outline-white transition duration-300 hover:bg-white">
                                        Subir
                                    </button>
                                </div>
                            </form>
                        </motion.div>
                    )}
                </AnimatePresence>
                <AnimatePresence>
                    {createView && (
                        <motion.div
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            exit={{ opacity: 0 }}
                            className="fixed top-0 left-0 z-50 flex h-full w-full items-center justify-center bg-black/50 text-left"
                        >
                            <form onSubmit={handleSubmit} method="POST" className="text-black">
                                <div className="w-[500px] rounded-md bg-white p-4">
                                    <h2 className="mb-4 text-2xl font-semibold">Crear Marca</h2>
                                    <div className="flex flex-col gap-4">
                                        <label htmlFor="ordennn">Orden</label>
                                        <input
                                            className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                            type="text"
                                            name="ordennn"
                                            id="ordennn"
                                            onChange={(e) => setData('order', e.target.value)}
                                        />
                                        <label htmlFor="nombree">
                                            Nombre <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                            type="text"
                                            name="nombree"
                                            id="nombree"
                                            onChange={(e) => setData('name', e.target.value)}
                                        />

                                        <div className="flex justify-end gap-4">
                                            <button
                                                type="button"
                                                onClick={() => setCreateView(false)}
                                                className="border-primary-orange text-primary-orange hover:bg-primary-orange rounded-md border px-2 py-1 transition duration-300 hover:text-white"
                                            >
                                                Cancelar
                                            </button>
                                            <button
                                                type="submit"
                                                className="border-primary-orange text-primary-orange hover:bg-primary-orange rounded-md border px-2 py-1 transition duration-300 hover:text-white"
                                            >
                                                Guardar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </motion.div>
                    )}
                </AnimatePresence>
                <div className="mx-auto flex w-full flex-col gap-3">
                    <h2 className="border-primary-orange text-primary-orange text-bold w-full border-b-2 text-2xl">Marcas</h2>
                    <div className="flex h-fit w-full flex-row gap-5">
                        <input
                            type="text"
                            placeholder="Buscar marca..."
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                            className="w-full rounded-md border border-gray-300 px-3"
                        />
                        <button
                            onClick={handleSearch}
                            className="bg-primary-orange w-[200px] rounded px-4 py-1 font-bold text-white hover:bg-orange-400"
                        >
                            Buscar
                        </button>
                        <button
                            onClick={() => setCreateView(true)}
                            className="bg-primary-orange w-[200px] rounded px-4 py-1 font-bold text-white hover:bg-orange-400"
                        >
                            Crear Marca
                        </button>
                        <button
                            onClick={() => setSubirMarcasYModelos(true)}
                            className="bg-primary-orange w-[400px] rounded px-4 py-1 font-bold text-white hover:bg-orange-400"
                        >
                            Subir Marcas y Modelos
                        </button>
                    </div>

                    <div className="flex w-full justify-center">
                        <table className="w-full border text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                            <thead className="bg-gray-300 text-sm font-medium text-black uppercase">
                                <tr>
                                    <td className="text-center">ORDEN</td>
                                    <td className="py-2 text-center">NOMBRE</td>

                                    <td className="text-center">EDITAR</td>
                                </tr>
                            </thead>
                            <tbody className="text-center">
                                {categorias.data?.map((categoria) => <CategoriasAdminRow key={categoria.id} categoria={categoria} />)}
                            </tbody>
                        </table>
                    </div>

                    {/* Paginación con datos del backend */}
                    <div className="mt-4 flex justify-center">
                        {categorias.links && (
                            <div className="flex items-center">
                                {categorias.links.map((link, index) => (
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
                                        } ${index === 0 ? 'rounded-l-md' : ''} ${index === categorias.links.length - 1 ? 'rounded-r-md' : ''}`}
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                ))}
                            </div>
                        )}
                    </div>

                    {/* Información de paginación */}
                    <div className="mt-2 text-center text-sm text-gray-600">
                        Mostrando {categorias.from || 0} a {categorias.to || 0} de {categorias.total} resultados
                    </div>
                </div>
            </div>
        </Dashboard>
    );
}
