import { Head, useForm, usePage } from '@inertiajs/react';
import toast from 'react-hot-toast';
import DefaultLayout from '../defaultLayout';

export default function Informacion() {
    const { informacion } = usePage().props;

    const { setData, post, processing } = useForm({
        fecha: '',
        importe: '',
        banco: '',
        sucursal: '',
        facturas: '',
        observaciones: '',
    });

    const sendInformacion = (e) => {
        e.preventDefault();
        post(route('sendInformacion'), {
            forceFormData: true,
            onSuccess: () => {
                toast.success('Información enviada correctamente');
                setData({
                    fecha: '',
                    importe: '',
                    banco: '',
                    sucursal: '',
                    facturas: '',
                    observaciones: '',
                });
            },
            onError: () => {
                toast.error('Error al enviar la información');
            },
        });
    };

    return (
        <DefaultLayout>
            <Head>
                <title>Información de pagos</title>
            </Head>
            <div className="mx-auto my-20 min-h-[50vh] w-[1200px]">
                <div className="flex flex-col gap-5">
                    <h2 className="text-[44px] font-semibold text-[#1A4791]">Información de pagos</h2>
                    <div className="flex flex-row gap-2">
                        <div className="flex w-full flex-col gap-2">
                            <h3 className="text-[16px] font-semibold">Cuentas bancarias para efectuar el depósito:</h3>
                            <div dangerouslySetInnerHTML={{ __html: informacion?.informacion }} />
                        </div>
                        <div className="h-[568px] w-[808px]">
                            <form onSubmit={sendInformacion} className="grid h-fit w-[808px] grid-cols-4 gap-6 bg-[#ECECEC] p-5">
                                <div className="col-span-2 flex flex-col gap-2">
                                    <label htmlFor="fecha">Fecha</label>
                                    <input
                                        onChange={(e) => setData('fecha', e.target.value)}
                                        type="text"
                                        id="fecha"
                                        className="h-[45px] border border-[#4D565D] pl-2"
                                    />
                                </div>
                                <div className="col-span-2 flex flex-col gap-2">
                                    <label htmlFor="importe">Importe</label>
                                    <input
                                        onChange={(e) => setData('importe', e.target.value)}
                                        type="text"
                                        id="importe"
                                        className="h-[45px] border border-[#4D565D] pl-2"
                                    />
                                </div>

                                {/* Crear un contenedor que ocupe las 4 columnas para los 3 campos */}
                                <div className="col-span-4 grid grid-cols-3 gap-6">
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="banco">Banco</label>
                                        <input
                                            onChange={(e) => setData('banco', e.target.value)}
                                            type="text"
                                            id="banco"
                                            className="h-[45px] border border-[#4D565D] pl-2"
                                        />
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="sucursal">Sucursal</label>
                                        <input
                                            onChange={(e) => setData('sucursal', e.target.value)}
                                            type="text"
                                            id="sucursal"
                                            className="h-[45px] border border-[#4D565D] pl-2"
                                        />
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="facturas">Facturas canceladas</label>
                                        <input
                                            onChange={(e) => setData('facturas', e.target.value)}
                                            type="text"
                                            id="facturas"
                                            className="h-[45px] border border-[#4D565D] pl-2"
                                        />
                                    </div>
                                </div>
                                <div className="col-span-4 flex flex-col gap-2">
                                    <label htmlFor="observaciones">Observaciones</label>
                                    <textarea
                                        onChange={(e) => setData('observaciones', e.target.value)}
                                        rows={6}
                                        id="observaciones"
                                        className="border border-[#4D565D] pl-2"
                                    />
                                </div>
                                <div className="col-span-2 flex flex-col justify-center gap-2">
                                    <label htmlFor="adjunto">Adjuntar archivos</label>
                                    <div className="flex h-[45px] flex-row items-center justify-between border border-[#4D565D] px-2 pl-2">
                                        <input onChange={(e) => setData('archivo', e.target.files[0])} type="file" id="adjunto" className="w-full" />
                                        <label htmlFor="adjunto" className="cursor-pointer">
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
                                                className="lucide lucide-upload-icon lucide-upload"
                                            >
                                                <path d="M12 3v12" />
                                                <path d="m17 8-5-5-5 5" />
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                            </svg>
                                        </label>
                                    </div>
                                </div>
                                <div className="col-span-2 flex flex-col justify-end gap-2">
                                    <div className="flex flex-row items-end justify-between">
                                        <p className="h-fit">*Campos obligatorios</p>
                                        <button type="submit" disabled={processing} className="bg-primary-orange h-[41px] w-[163px] text-white">
                                            Enviar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </DefaultLayout>
    );
}
