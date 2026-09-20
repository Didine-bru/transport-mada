<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

import { Users, Truck, Bus, ArrowRight, Sparkles } from "lucide-vue-next";

defineProps({
    auth: Object,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Tableau de bord
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Bienvenue -->
                <div
                    class="mb-8 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition duration-300 hover:shadow-md"
                >
                    <div class="relative p-7 sm:p-8">
                        <!-- Petite décoration -->
                        <div
                            class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-blue-50"
                        ></div>

                        <div class="relative">
                            <h3 class="flex items-center text-2xl font-bold tracking-tight text-gray-900">
                                Bienvenue, {{ auth.user.name }}

                                <Sparkles
                                    class="ml-2 h-6 w-6 text-blue-500 transition-transform duration-300 hover:rotate-12 hover:scale-110"
                                />
                            </h3>

                            <p class="mt-2 text-gray-500">
                                Heureux de vous revoir dans votre espace de
                                gestion.
                            </p>

                            <div class="mt-4 flex items-center gap-2">
                                <span
                                    class="inline-flex items-center rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700"
                                >
                                    {{ auth.user.role }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modules -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <!-- Utilisateurs -->
                    <Link
                        v-if="auth.user.role === 'admin'"
                        :href="route('users.index')"
                        class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <!-- Décoration -->
                        <div
                             class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-blue-50 transition-transform duration-500 group-hover:scale-125"
                        ></div>

                        <div class="relative">
                            <!-- Icon -->
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-xl bg-blue-100 text-blue-600 transition-all duration-300 group-hover:scale-110 group-hover:rotate-3"
                            >
                                <Users class="h-7 w-7" />
                            </div>

                            <h3 class="mt-5 text-xl font-bold text-gray-900">
                                Utilisateurs
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Gérez les comptes utilisateurs et leurs rôles.
                            </p>

                            <div
                                class="mt-5 flex items-center gap-2 text-sm font-semibold text-blue-600"
                            >
                                <span>Accéder</span>

                                <ArrowRight
                                    class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1"
                                />
                            </div>
                        </div>
                    </Link>

                    <!-- Transporteurs -->
                    <Link
                        v-if="auth.user.role === 'admin'"
                        :href="route('transporters.index')"
                        class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <!-- Décoration -->
                        <div
                            class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-emerald-50 transition-transform duration-500 group-hover:scale-125"
                        ></div>

                        <div class="relative">
                            <!-- Icon -->
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 transition-all duration-300 group-hover:scale-110 group-hover:rotate-3"
                            >
                                <Truck class="h-7 w-7" />
                            </div>

                            <h3 class="mt-5 text-xl font-bold text-gray-900">
                                Transporteurs
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Gérez les transporteurs et leurs informations.
                            </p>

                            <div
                                class="mt-5 flex items-center gap-2 text-sm font-semibold text-emerald-600"
                            >
                                <span>Accéder</span>

                                <ArrowRight
                                    class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1"
                                />
                            </div>
                        </div>
                    </Link>

                    <!-- Véhicules -->
                    <Link
                        v-if="auth.user.role === 'admin'"
                        :href="route('vehicles.index')"
                        class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <!-- Décoration -->
                        <div
                            class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-orange-50 transition-transform duration-500 group-hover:scale-125"
                        ></div>

                        <div class="relative">
                            <!-- Icon -->
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-xl bg-orange-100 text-orange-600 transition-all duration-300 group-hover:scale-110 group-hover:rotate-3"
                            >
                                <Bus class="h-7 w-7" />
                            </div>

                            <h3 class="mt-5 text-xl font-bold text-gray-900">
                                Véhicules
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Gérez les véhicules associés aux transporteurs.
                            </p>

                            <div
                                class="mt-5 flex items-center gap-2 text-sm font-semibold text-orange-600"
                            >
                                <span>Accéder</span>

                                <ArrowRight
                                    class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1"
                                />
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
