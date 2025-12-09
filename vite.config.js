import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/style.css',
                'resources/js/main.js',
                'resources/css/dashboard.css',
                'resources/js/dashboard.js',
                'resources/css/verifikasi.css',
                'resources/js/verifikasi.js',
                'resources/css/stok.css',
                'resources/js/stok.js',
                'resources/css/manage-users.css',
                'resources/js/manage-users.js',
                'resources/css/master-material.css',
                'resources/css/master-material.js',
                'resources/css/penerimaan-modulel.css',
                'resources/css/penerimaan-modulel.js',
            ],
            refresh: true,
        }),
    ],
});