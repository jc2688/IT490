import { defineConfig } from "vite";
import reactRefresh from '@vitejs/plugin-reactRefresh'; 
import svgrPlugin from 'vite-plugin-svgr';

export default defineConfig({
    build: {
        outDir: 'build',
    },
    plugins: [
        reactRefresh(),
        svgrPlugin9({
            svgrOptions: {
                icon: true,
            },
        }),
    ],    
})