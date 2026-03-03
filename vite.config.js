import { defineConfig } from 'vite';

export default defineConfig({
    publicDir: false,
    css: {
        preprocessorOptions: {
            scss: {
                quietDeps: true,
            },
        },
    },
    build: {
        outDir: 'public/frontend/assets',
        manifest: 'manifest.json',
        emptyOutDir: false,
        rollupOptions: {
            input: {
                app: 'frontend/assets/js/app.js',
                style: 'frontend/assets/scss/app.scss',
            },
            output: {
                entryFileNames: 'js/[name]-[hash].js',
                chunkFileNames: 'js/[name]-[hash].js',
                assetFileNames: ({ name }) => {
                    if (name && name.endsWith('.css')) {
                        return 'css/app-[hash][extname]';
                    }
                    return 'assets/[name]-[hash][extname]';
                },
            },
        },
    },
});
