import { Head } from '@inertiajs/react';
import DefaultLayout from '../defaultLayout';

export default function Margenes() {
    const changeMargen = (e) => {
        localStorage.setItem('margen', e.target.value);
    };

    return (
        <DefaultLayout>
            <Head>
                <title>Márgenes</title>
            </Head>
            <div className="mx-auto my-20 min-h-[50vh] w-[1200px]">
                <div className="flex flex-col gap-5">
                    <h2 className="text-[44px] font-semibold text-[#1A4791]">Márgenes</h2>
                    <div className="flex flex-col gap-2">
                        <label htmlFor="margen">Márgen sobre lista de precios</label>
                        <div className="flex h-[48px] w-[183px] items-center justify-between border">
                            <input
                                defaultValue={localStorage.getItem('margen') || 0}
                                onChange={changeMargen}
                                type="number"
                                id="margen"
                                className="h-full w-[80%] pl-2 outline-none"
                            />
                            <span className="flex h-full w-[20%] items-center justify-center border-l-2">%</span>
                        </div>
                    </div>
                </div>
            </div>
        </DefaultLayout>
    );
}
