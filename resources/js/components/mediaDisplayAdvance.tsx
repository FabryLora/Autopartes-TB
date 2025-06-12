import { useEffect, useState } from 'react';

const MediaDisplayAdvanced = ({ mediaPath }) => {
    const [mediaType, setMediaType] = useState(null);
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
        if (!mediaPath) {
            setIsLoading(false);
            return;
        }

        // Función para verificar el tipo MIME real del archivo
        const checkMimeType = async () => {
            try {
                const response = await fetch(mediaPath, { method: 'HEAD' });
                const contentType = response.headers.get('content-type');

                if (contentType.startsWith('image/')) {
                    setMediaType('image');
                } else if (contentType.startsWith('video/')) {
                    setMediaType('video');
                } else {
                    setMediaType('unknown');
                }
            } catch (error) {
                console.error('Error al verificar el tipo de archivo:', error);
                setMediaType('unknown');
            } finally {
                setIsLoading(false);
            }
        };

        checkMimeType();
    }, [mediaPath]);

    if (isLoading) {
        return <p>Cargando...</p>;
    }

    if (!mediaPath) {
        return <p>No hay archivo multimedia disponible</p>;
    }

    if (mediaType === 'image') {
        return <img src={mediaPath} alt="Imagen" className="h-auto max-w-full" />;
    } else if (mediaType === 'video') {
        return (
            <video src={mediaPath} controls className="h-auto max-w-full">
                Tu navegador no soporta la reproducción de videos.
            </video>
        );
    }

    // Si no se puede determinar el tipo de archivo
    return <p>Formato de archivo no soportado: {mediaPath}</p>;
};

export default MediaDisplayAdvanced;
