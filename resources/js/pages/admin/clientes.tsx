import ClientesAdminRow from '@/components/clientesAdminRow';
import { router, useForm, usePage } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { useEffect, useState } from 'react';
import toast from 'react-hot-toast';
import Select from 'react-select';
import Dashboard from './dashboard';

export default function Clientes() {
    const { clientes, provincias, vendedores, listas, sucursales } = usePage().props;
    const [searchTerm, setSearchTerm] = useState('');
    const [createView, setCreateView] = useState(false);
    const [subirView, setSubirView] = useState(false);
    const [archivo, setArchivo] = useState();
    const [sucursalesSelected, setSucursalesSelected] = useState([]);
    const { data, setData, post, reset, errors } = useForm({
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
        lista_de_precios_id: '',
        rol: 'cliente',
        autorizado: 1,
        sucursales: [],
    });

    const signup = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        signupForm.post(route('register.store'), {
            onSuccess: () => setCreateView(false),
            onError: (error) => console.error('Error al registrar:', error),
        });
    };

    useEffect(() => {
        signupForm.setData(
            'sucursales',
            sucursalesSelected.map((m) => m.value),
        );
    }, [sucursalesSelected]);

    // Manejadores para la paginación del backend
    const handlePageChange = (page) => {
        router.get(
            route('admin.clientes'),
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
            route('admin.clientes'),
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

    const importarClientes = (e) => {
        e.preventDefault();

        router.post(
            route('importarClientes'),
            {
                archivo: archivo,
            },
            {
                onSuccess: () => {
                    toast.success('Clientes importados correctamente');
                    reset();
                    setSubirView(false);
                },
                onError: (errors) => {
                    toast.error('Error al importar cientes');
                    console.log(errors);
                },
            },
        );
    };

    const inputClass = (hasError: boolean) =>
        `h-[45px] w-full pl-3 outline-1 transition duration-300 ${
            hasError ? 'outline-red-500 focus:outline-red-500' : 'outline-[#DDDDE0] focus:outline-primary-orange'
        }`;

    // helper: texto de error
    const FieldError = ({ msg }: { msg?: string }) => (msg ? <p className="mt-1 text-sm leading-4 text-red-600">{msg}</p> : null);

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
                            <form
                                onSubmit={signup}
                                className="flex h-fit max-h-[90vh] w-[600px] flex-col gap-6 overflow-y-auto bg-white p-5 shadow-md"
                                noValidate
                            >
                                <h2 className="text-xl font-bold text-black">Registrar cliente</h2>

                                <div className="grid w-full grid-cols-2 gap-3 text-[16px]">
                                    {/* Nombre */}
                                    <div className="col-span-2 flex flex-col gap-2">
                                        <label htmlFor="name">Nombre de usuario</label>
                                        <input
                                            id="name"
                                            name="name"
                                            type="text"
                                            className={inputClass(!!signupForm.errors.name)}
                                            onChange={(e) => signupForm.setData('name', e.target.value)}
                                            required
                                            aria-invalid={!!signupForm.errors.name}
                                        />
                                        <FieldError msg={signupForm.errors.name} />
                                    </div>

                                    {/* Password */}
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="password">Contraseña</label>
                                        <input
                                            id="password"
                                            name="password"
                                            type="password"
                                            className={inputClass(!!signupForm.errors.password)}
                                            onChange={(e) => signupForm.setData('password', e.target.value)}
                                            required
                                            aria-invalid={!!signupForm.errors.password}
                                        />
                                        <FieldError msg={signupForm.errors.password} />
                                    </div>

                                    {/* Confirmación */}
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="password_confirmation">Confirmar contraseña</label>
                                        <input
                                            id="password_confirmation"
                                            name="password_confirmation"
                                            type="password"
                                            className={inputClass(!!signupForm.errors.password_confirmation)}
                                            onChange={(e) => signupForm.setData('password_confirmation', e.target.value)}
                                            required
                                            aria-invalid={!!signupForm.errors.password_confirmation}
                                        />
                                        <FieldError msg={signupForm.errors.password_confirmation} />
                                    </div>

                                    {/* Email principal */}
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="email">Email</label>
                                        <input
                                            id="email"
                                            name="email"
                                            type="email"
                                            className={inputClass(!!signupForm.errors.email)}
                                            onChange={(e) => signupForm.setData('email', e.target.value)}
                                            aria-invalid={!!signupForm.errors.email}
                                        />
                                        <FieldError msg={signupForm.errors.email} />
                                    </div>

                                    {/* Emails extras */}
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="email2">Email 2</label>
                                        <input
                                            id="email2"
                                            name="email_dos"
                                            type="email"
                                            className={inputClass(!!signupForm.errors.email_dos)}
                                            onChange={(e) => signupForm.setData('email_dos', e.target.value)}
                                            aria-invalid={!!signupForm.errors.email_dos}
                                        />
                                        <FieldError msg={signupForm.errors.email_dos} />
                                    </div>

                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="email3">Email 3</label>
                                        <input
                                            id="email3"
                                            name="email_tres"
                                            type="email"
                                            className={inputClass(!!signupForm.errors.email_tres)}
                                            onChange={(e) => signupForm.setData('email_tres', e.target.value)}
                                            aria-invalid={!!signupForm.errors.email_tres}
                                        />
                                        <FieldError msg={signupForm.errors.email_tres} />
                                    </div>

                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="email4">Email 4</label>
                                        <input
                                            id="email4"
                                            name="email_cuatro"
                                            type="email"
                                            className={inputClass(!!signupForm.errors.email_cuatro)}
                                            onChange={(e) => signupForm.setData('email_cuatro', e.target.value)}
                                            aria-invalid={!!signupForm.errors.email_cuatro}
                                        />
                                        <FieldError msg={signupForm.errors.email_cuatro} />
                                    </div>

                                    {/* Razón social */}
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="razon_social">Razón social</label>
                                        <input
                                            id="razon_social"
                                            name="razon_social"
                                            type="text"
                                            className={inputClass(!!signupForm.errors.razon_social)}
                                            onChange={(e) => signupForm.setData('razon_social', e.target.value)}
                                            aria-invalid={!!signupForm.errors.razon_social}
                                        />
                                        <FieldError msg={signupForm.errors.razon_social} />
                                    </div>

                                    {/* CUIT */}
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="cuit">CUIT</label>
                                        <input
                                            id="cuit"
                                            name="cuit"
                                            type="text"
                                            className={inputClass(!!signupForm.errors.cuit)}
                                            onChange={(e) => signupForm.setData('cuit', e.target.value)}
                                            required
                                            aria-invalid={!!signupForm.errors.cuit}
                                        />
                                        <FieldError msg={signupForm.errors.cuit} />
                                    </div>

                                    {/* Dirección */}
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="direccion">Dirección</label>
                                        <input
                                            id="direccion"
                                            name="direccion"
                                            type="text"
                                            className={inputClass(!!signupForm.errors.direccion)}
                                            onChange={(e) => signupForm.setData('direccion', e.target.value)}
                                            required
                                            aria-invalid={!!signupForm.errors.direccion}
                                        />
                                        <FieldError msg={signupForm.errors.direccion} />
                                    </div>

                                    {/* Teléfono */}
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="telefono">Teléfono</label>
                                        <input
                                            id="telefono"
                                            name="telefono"
                                            type="text"
                                            className={inputClass(!!signupForm.errors.telefono)}
                                            onChange={(e) => signupForm.setData('telefono', e.target.value)}
                                            required
                                            aria-invalid={!!signupForm.errors.telefono}
                                        />
                                        <FieldError msg={signupForm.errors.telefono} />
                                    </div>

                                    {/* Lista de precios */}
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="lista_de_precios_id">Lista</label>
                                        <select
                                            id="lista_de_precios_id"
                                            name="lista_de_precios_id"
                                            className={inputClass(!!signupForm.errors.lista_de_precios_id)}
                                            onChange={(e) => signupForm.setData('lista_de_precios_id', e.target.value)}
                                            aria-invalid={!!signupForm.errors.lista_de_precios_id}
                                            defaultValue=""
                                        >
                                            <option disabled value="">
                                                Selecciona una lista
                                            </option>
                                            {listas?.map((l) => (
                                                <option key={l.id} value={l.id}>
                                                    {l.name}
                                                </option>
                                            ))}
                                        </select>
                                        <FieldError msg={signupForm.errors.lista_de_precios_id} />
                                    </div>

                                    {/* Vendedor */}
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="vendedor_id">Vendedor</label>
                                        <select
                                            id="vendedor_id"
                                            name="vendedor_id"
                                            className={inputClass(!!signupForm.errors.vendedor_id)}
                                            onChange={(e) => signupForm.setData('vendedor_id', e.target.value)}
                                            aria-invalid={!!signupForm.errors.vendedor_id}
                                            defaultValue=""
                                        >
                                            <option disabled value="">
                                                Selecciona un vendedor
                                            </option>
                                            {vendedores?.map((v) => (
                                                <option key={v.id} value={v.id}>
                                                    {v.name}
                                                </option>
                                            ))}
                                        </select>
                                        <FieldError msg={signupForm.errors.vendedor_id} />
                                    </div>

                                    {/* Descuentos */}
                                    <div className="col-span-2 grid grid-cols-3 gap-4">
                                        <div className="flex flex-col gap-2">
                                            <label htmlFor="descuento_uno">Descuento 1</label>
                                            <input
                                                id="descuento_uno"
                                                type="number"
                                                className={inputClass(!!signupForm.errors.descuento_uno)}
                                                onChange={(e) => signupForm.setData('descuento_uno', e.target.value)}
                                                aria-invalid={!!signupForm.errors.descuento_uno}
                                            />
                                            <FieldError msg={signupForm.errors.descuento_uno} />
                                        </div>
                                        <div className="flex flex-col gap-2">
                                            <label htmlFor="descuento_dos">Descuento 2</label>
                                            <input
                                                id="descuento_dos"
                                                type="number"
                                                className={inputClass(!!signupForm.errors.descuento_dos)}
                                                onChange={(e) => signupForm.setData('descuento_dos', e.target.value)}
                                                aria-invalid={!!signupForm.errors.descuento_dos}
                                            />
                                            <FieldError msg={signupForm.errors.descuento_dos} />
                                        </div>
                                        <div className="flex flex-col gap-2">
                                            <label htmlFor="descuento_tres">Descuento 3</label>
                                            <input
                                                id="descuento_tres"
                                                type="number"
                                                className={inputClass(!!signupForm.errors.descuento_tres)}
                                                onChange={(e) => signupForm.setData('descuento_tres', e.target.value)}
                                                aria-invalid={!!signupForm.errors.descuento_tres}
                                            />
                                            <FieldError msg={signupForm.errors.descuento_tres} />
                                        </div>
                                    </div>

                                    {/* Sucursales (react-select) */}
                                    <div className="col-span-2 flex flex-col gap-2">
                                        <label htmlFor="sucursal">Sucursales</label>
                                        <Select
                                            inputId="sucursal"
                                            options={sucursales?.map((s) => ({ value: s.id, label: s.name }))}
                                            onChange={(opts) => setSucursalesSelected(opts as any[])}
                                            isMulti
                                            classNamePrefix="rs"
                                        />
                                        <FieldError msg={signupForm.errors.sucursales as unknown as string} />
                                    </div>

                                    {/* Provincia */}
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="provincia">Provincia</label>
                                        <select
                                            id="provincia"
                                            name="provincia"
                                            required
                                            className={inputClass(!!signupForm.errors.provincia)}
                                            onChange={(e) => signupForm.setData('provincia', e.target.value)}
                                            aria-invalid={!!signupForm.errors.provincia}
                                            defaultValue=""
                                        >
                                            <option disabled value="">
                                                Selecciona una provincia
                                            </option>
                                            {provincias?.map((pr) => (
                                                <option key={pr.id} value={pr.name}>
                                                    {pr.name}
                                                </option>
                                            ))}
                                        </select>
                                        <FieldError msg={signupForm.errors.provincia} />
                                    </div>

                                    {/* Localidad dependiente */}
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="localidad">Localidad</label>
                                        <select
                                            id="localidad"
                                            name="localidad"
                                            required
                                            className={inputClass(!!signupForm.errors.localidad)}
                                            onChange={(e) => signupForm.setData('localidad', e.target.value)}
                                            aria-invalid={!!signupForm.errors.localidad}
                                            value={signupForm.data.localidad || ''}
                                        >
                                            <option disabled value="">
                                                Selecciona una localidad
                                            </option>
                                            {provincias
                                                ?.find((pr) => pr.name === signupForm.data.provincia)
                                                ?.localidades?.map((loc: any, i: number) => (
                                                    <option key={i} value={loc.name}>
                                                        {loc.name}
                                                    </option>
                                                ))}
                                        </select>
                                        <FieldError msg={signupForm.errors.localidad} />
                                    </div>
                                </div>

                                {/* acciones */}
                                <div className="flex flex-row justify-between gap-4">
                                    <button
                                        type="button"
                                        onClick={() => setCreateView(false)}
                                        className="bg-primary-orange col-span-2 h-[43px] w-full text-white"
                                    >
                                        Cancelar
                                    </button>
                                    <button disabled={signupForm.processing} className="bg-primary-orange col-span-2 h-[43px] w-full text-white">
                                        {signupForm.processing ? 'Registrando...' : 'Registrar cliente'}
                                    </button>
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
                            <form onSubmit={importarClientes} method="POST" className="relative rounded-lg bg-white text-black">
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
                                    <h2 className="text-2xl font-semibold text-white">Subir Clientes</h2>
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
                    <h2 className="border-primary-orange text-primary-orange text-bold w-full border-b-2 text-2xl">Clientes</h2>
                    <div className="flex h-fit w-full flex-row gap-5">
                        <input
                            type="text"
                            placeholder="Buscar cliente..."
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
                            Registrar cliente
                        </button>
                        <button
                            onClick={() => setSubirView(true)}
                            className="bg-primary-orange w-[400px] rounded px-4 py-1 font-bold text-white hover:bg-orange-400"
                        >
                            Subir clientes
                        </button>
                    </div>

                    <div className="flex w-full justify-center">
                        <table className="w-full border text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                            <thead className="bg-gray-300 text-sm font-medium text-black uppercase">
                                <tr>
                                    <td className="pl-5 text-left">CLIENTE</td>
                                    <td className="text-left">EMAIL</td>
                                    <td className="py-2 text-left">PROVINCIA</td>
                                    <td className="text-left">LOCALIDAD</td>
                                    <td className="text-left">VENDEDOR</td>
                                    <td className="text-center">LISTA</td>
                                    <td className="text-center">AUTORIZADO</td>

                                    <td className="text-center">EDITAR</td>
                                </tr>
                            </thead>
                            <tbody className="text-center">
                                {clientes.data?.map((cliente) => <ClientesAdminRow key={cliente.id} cliente={cliente} />)}
                            </tbody>
                        </table>
                    </div>

                    {/* Paginación con datos del backend */}
                    <div className="mt-4 flex justify-center">
                        {clientes.links && (
                            <div className="flex items-center">
                                {clientes.links.map((link, index) => (
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
                                        } ${index === 0 ? 'rounded-l-md' : ''} ${index === clientes.links.length - 1 ? 'rounded-r-md' : ''}`}
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                ))}
                            </div>
                        )}
                    </div>

                    {/* Información de paginación */}
                    <div className="mt-2 text-center text-sm text-gray-600">
                        Mostrando {clientes.from || 0} a {clientes.to || 0} de {clientes.total} resultados
                    </div>
                </div>
            </div>
        </Dashboard>
    );
}
