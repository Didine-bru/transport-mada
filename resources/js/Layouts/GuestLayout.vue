<script setup>
import { Link } from "@inertiajs/vue3";
import { Bus } from "lucide-vue-next";
</script>

<template>
    <div
        class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden bg-slate-50 px-6 py-12"
    >
        <!-- Décoration indigo -->
        <div
            class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-indigo-50"
        ></div>

        <!-- Décoration violet -->
        <div
            class="absolute -bottom-20 -left-20 h-72 w-72 rounded-full bg-violet-50"
        ></div>

        <!-- Petite décoration centrale -->
        <div
            class="absolute left-1/2 top-0 h-40 w-40 -translate-x-1/2 rounded-full bg-indigo-50/60"
        ></div>

        <div class="relative z-10 w-full max-w-md">
            <!-- Logo -->
            <div class="mb-8 text-center">
                <Link
                    href="/"
                    class="inline-flex items-center text-2xl font-bold tracking-tight text-indigo-600"
                >
                    <div
                        class="mr-3 flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600"
                    >
                        <Bus :size="22" />
                    </div>

                    Transport Mada
                </Link>

                <p class="mt-3 text-sm text-gray-500">
                    Gérez vos déplacements simplement
                </p>
            </div>

            <!-- Carte de connexion -->
            <div
                class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white px-7 py-9 shadow-sm transition duration-300 hover:shadow-md"
            >
                <!-- Petite décoration de la carte -->
                <div
                    class="absolute -right-10 -top-10 h-28 w-28 rounded-full bg-indigo-50"
                ></div>

                <div class="relative">
                    <slot />
                </div>
            </div>

            <!-- Retour -->
            <div class="mt-6 text-center">
                <Link
                    href="/"
                    class="text-sm text-gray-500 transition hover:text-indigo-600"
                >
                    ← Retour à l'accueil
                </Link>
            </div>
        </div>
    </div>
</template>
