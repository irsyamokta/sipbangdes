import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { Toaster } from 'sonner';
import { DebugProvider } from "@/Context/DebugContext";
import DebugPanel from "@/Components/debug/DebugPanel";

const appName = import.meta.env.PROD || 'Sipbangdes';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.tsx`,
            import.meta.glob('./Pages/**/*.tsx'),
        ),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(
            <>
                <DebugProvider>
                    <DebugPanel />
                    <App {...props} />
                </DebugProvider>
                <Toaster
                    richColors
                    duration={3000}
                />
            </>
        );
    },
    progress: {
        color: '#163C74',
    },
});
