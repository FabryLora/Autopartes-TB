import VendedoresAdminRow from '@/components/vendedoresAdminRow copy';
import { router, useForm, usePage } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { useState } from 'react';
import toast from 'react-hot-toast';
import Dashboard from './dashboard';

export default function Vendedores() {
    const { vendedores, provincias } = usePage().props;

    const { data, setData, post, reset } = useForm({
        name: '',
    });

    const signupForm = useForm({
        name: '',
        password: '',
        password_confirmation: '',
        email: '',
        cuit: '',
        direccion: '',
        provincia: '',
        localidad: '',
        telefono: '',
        descuento_uno: 0,
        descuento_dos: 0,
        descuento_tres: 0,
        rol: 'vendedor',
        autorizado: 1,
    });

    const signup = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        signupForm.post(route('register'), {
            onSuccess: () => {
                setCreateView(false);
                toast.success('Vendedor registrado correctamente');
            },
            onError: (error) => {
                console.error('Error al registrar el vendedor:', error);
                toast.error('Error al registrar el vendedor. Por favor, verifica los datos ingresados.');
            },
        });
    };

    const [searchTerm, setSearchTerm] = useState('');
    const [createView, setCreateView] = useState(false);
    const [subirView, setSubirView] = useState(false);
    const [archivo, setArchivo] = useState();

    // Manejadores para la paginación del backend
    const handlePageChange = (page) => {
        router.get(
            route('admin.vendedores'),
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
            route('admin.vendedores'),
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

    const importarVendedores = (e) => {
        e.preventDefault();

        router.post(
            route('importarVendedores'),
            {
                archivo: archivo,
            },
            {
                onSuccess: () => {
                    toast.success('Vendedores importados correctamente');
                    reset();
                    setSubirView(false);
                },
                onError: (errors) => {
                    toast.error('Error al importar vendedores');
                    console.log(errors);
                },
            },
        );
    };

    return (
        <Dashboard>
            <div className="flex w-full flex-col p-6">
                <AnimatePresence>
                    {createView && (
                        <motion.div
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            exit={{ opacity: 0 }}
                            className="fixed top-0 left-0 z-50 flex h-full w-full items-center justify-center bg-black/50 text-left"
                        >
                            <form onSubmit={signup} className="flex h-fit w-[600px] flex-col gap-6 bg-white p-5 shadow-md">
                                <h2 className="text-xl font-bold text-black">Registrar vendedor</h2>
                                <div className="grid w-full grid-cols-2 gap-3 text-[16px]">
                                    <div className="col-span-2 flex flex-col gap-2">
                                        <label htmlFor="name" className="">
                                            Nombre de usuario
                                        </label>
                                        <input
                                            onChange={(ev) => signupForm.setData('name', ev.target.value)}
                                            className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                            type="text"
                                            name="name"
                                            id="name"
                                            required
                                        />
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="password">Contraseña</label>
                                        <input
                                            onChange={(ev) => signupForm.setData('password', ev.target.value)}
                                            className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                            type="password"
                                            name="password"
                                            id="password"
                                            required
                                        />
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="password_confirmation">Confirmar contraseña</label>
                                        <input
                                            onChange={(ev) => signupForm.setData('password_confirmation', ev.target.value)}
                                            className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                            type="password"
                                            name="password_confirmation"
                                            id="password_confirmation"
                                            required
                                        />
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="email">Email</label>
                                        <input
                                            onChange={(ev) => signupForm.setData('email', ev.target.value)}
                                            className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                            type="email"
                                            name="email"
                                            id="email"
                                            required
                                        />
                                    </div>

                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="dni">Cuit</label>
                                        <input
                                            onChange={(ev) => signupForm.setData('cuit', ev.target.value)}
                                            className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                            type="text"
                                            name="dni"
                                            id="dni"
                                            required
                                        />
                                    </div>

                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="direccion">Dirección</label>
                                        <input
                                            onChange={(ev) => signupForm.setData('direccion', ev.target.value)}
                                            className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                            type="text"
                                            name="direccion"
                                            id="direccion"
                                            required
                                        />
                                    </div>

                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="telefono">Telefono</label>
                                        <input
                                            onChange={(ev) => signupForm.setData('telefono', ev.target.value)}
                                            className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                            type="text"
                                            name="telefono"
                                            id="telefono"
                                            required
                                        />
                                    </div>

                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="provincia">Provincia</label>
                                        <select
                                            required
                                            onChange={(ev) => signupForm.setData('provincia', ev.target.value)}
                                            className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                            name="provincia"
                                            id="provincia"
                                        >
                                            <option disabled selected value="">
                                                Selecciona una provincia
                                            </option>

                                            {provincias?.map((pr) => (
                                                <option key={pr.id} value={pr.name}>
                                                    {pr.name}
                                                </option>
                                            ))}
                                        </select>
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="localidad">Localidad</label>
                                        <select
                                            required
                                            onChange={(ev) => signupForm.setData('localidad', ev.target.value)}
                                            className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                            name="localidad"
                                            id="localidad"
                                        >
                                            <option disabled selected value="">
                                                Selecciona una localidad
                                            </option>

                                            {provincias
                                                ?.find((pr) => pr.name === signupForm?.data?.provincia)
                                                ?.localidades.map((loc, index) => (
                                                    <option key={index} value={loc.name}>
                                                        {loc.name}
                                                    </option>
                                                ))}
                                        </select>
                                    </div>
                                </div>
                                <div className="flex flex-row justify-between gap-4">
                                    <button
                                        type="button"
                                        onClick={() => setCreateView(false)}
                                        className="bg-primary-orange col-span-2 h-[43px] w-full text-white"
                                    >
                                        Cancelar
                                    </button>
                                    <button className="bg-primary-orange col-span-2 h-[43px] w-full text-white">Regsitrar cliente</button>
                                </div>
                            </form>
                        </motion.div>
                    )}
                </AnimatePresence>
                <AnimatePresence>
                    {subirView && (
                        <motion.div
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            exit={{ opacity: 0 }}
                            className="fixed top-0 left-0 z-50 flex h-full w-full items-center justify-center bg-black/50 text-left"
                        >
                            <form onSubmit={importarVendedores} method="POST" className="relative rounded-lg bg-white text-black">
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
                                    <h2 className="text-2xl font-semibold text-white">Subir Vendedores</h2>
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
                                        onClick={() => setSubirView(false)}
                                        className="rounded-md border border-red-500 bg-red-500 px-2 py-1 text-white transition duration-300"
                                    >
                                        Cancelar
                                    </button>
                                    <button
                                        type="submit"
                                        className="hover:text-primary-orange rounded-md px-2 py-1 text-white outline outline-white transition duration-300 hover:bg-white"
                                    >
                                        Subir
                                    </button>
                                </div>
                            </form>
                        </motion.div>
                    )}
                </AnimatePresence>
                <div className="mx-auto flex w-full flex-col gap-3">
                    <h2 className="border-primary-orange text-primary-orange text-bold w-full border-b-2 text-2xl">Vendedores</h2>
                    <div className="flex h-fit w-full flex-row gap-5">
                        <input
                            type="text"
                            placeholder="Buscar vendedor..."
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
                            className="bg-primary-orange w-[400px] rounded px-4 py-1 font-bold text-white hover:bg-orange-400"
                        >
                            Registrar vendedor
                        </button>
                        <button
                            onClick={() => setSubirView(true)}
                            className="bg-primary-orange w-[400px] rounded px-4 py-1 font-bold text-white hover:bg-orange-400"
                        >
                            Subir vendedores
                        </button>
                    </div>

                    <div className="flex w-full justify-center">
                        <table className="w-full border text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                            <thead className="bg-gray-300 text-sm font-medium text-black uppercase">
                                <tr>
                                    <td className="pl-3">VENDEDOR</td>

                                    <td className="py-2">EMAIL</td>
                                    <td className="py-2">AUTORIZADO</td>

                                    <td className="text-center">EDITAR</td>
                                </tr>
                            </thead>
                            <tbody className="text-center">
                                {vendedores.data?.map((vendedor) => <VendedoresAdminRow key={vendedor.id} vendedor={vendedor} />)}
                            </tbody>
                        </table>
                    </div>

                    {/* Paginación con datos del backend */}
                    <div className="mt-4 flex justify-center">
                        {vendedores.links && (
                            <div className="flex items-center">
                                {vendedores.links.map((link, index) => (
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
                                        } ${index === 0 ? 'rounded-l-md' : ''} ${index === vendedores.links.length - 1 ? 'rounded-r-md' : ''}`}
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                ))}
                            </div>
                        )}
                    </div>

                    {/* Información de paginación */}
                    <div className="mt-2 text-center text-sm text-gray-600">
                        Mostrando {vendedores.from || 0} a {vendedores.to || 0} de {vendedores.total} resultados
                    </div>
                </div>
            </div>
        </Dashboard>
    );
}
