<script setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    auth: Object,
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Transport Mada" />

    <div class="min-h-screen bg-slate-50 text-slate-800">
        <!-- Navigation -->
        <nav class="border-b border-slate-200 bg-white">
            <div
                class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4"
            >
                <!-- Logo -->
                <Link href="/" class="text-xl font-bold text-green-600">
                    Transport Mada
                </Link>

                <!-- Menu -->
                <div class="hidden items-center gap-8 md:flex">
                    <Link href="/" class="text-sm font-medium text-green-600">
                        Accueil
                    </Link>

                    <a
                        href="#trajets"
                        class="text-sm font-medium text-slate-600 transition hover:text-green-600"
                    >
                        Trajets
                    </a>

                    <a
                        href="#apropos"
                        class="text-sm font-medium text-slate-600 transition hover:text-green-600"
                    >
                        À propos
                    </a>

                    <Link
                        href="/login"
                        class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-green-700"
                    >
                        Se connecter
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Hero -->
        <section
            class="relative overflow-hidden bg-gradient-to-br from-green-600 to-green-700"
        >
            <div
                class="mx-auto max-w-7xl px-6 py-20 lg:flex lg:items-center lg:justify-between lg:gap-12"
            >
                <!-- Texte -->
                <div class="max-w-2xl text-white">
                    <p
                        class="mb-4 text-sm font-semibold uppercase tracking-wider text-green-100"
                    >
                        Votre voyage commence ici
                    </p>

                    <h1
                        class="text-4xl font-extrabold leading-tight sm:text-5xl lg:text-6xl"
                    >
                        Réservez votre voyage en toute simplicité
                    </h1>

                    <p class="mt-6 max-w-xl text-lg leading-8 text-green-50">
                        Trouvez facilement votre trajet, choisissez votre
                        transporteur et réservez vos places en quelques clics.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4">
                        <a
                            href="#trajets"
                            class="rounded-lg bg-white px-6 py-3 text-sm font-semibold text-green-700 shadow-sm transition hover:bg-green-50"
                        >
                            Rechercher un trajet
                        </a>

                        <Link
                            href="/login"
                            class="rounded-lg border border-white/40 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10"
                        >
                            Se connecter
                        </Link>
                    </div>
                </div>

                <!-- Carte visuelle -->
                <div class="mt-12 w-full max-w-md lg:mt-0">
                    <div class="rounded-2xl bg-white p-6 shadow-2xl">
                        <div class="mb-5">
                            <h2 class="text-xl font-bold text-slate-800">
                                Rechercher un trajet
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Trouvez le trajet qui vous convient.
                            </p>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label
                                    class="mb-1 block text-sm font-medium text-slate-700"
                                >
                                    Départ
                                </label>

                                <input
                                    type="text"
                                    placeholder="Ex : Antananarivo"
                                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                />
                            </div>

                            <div>
                                <label
                                    class="mb-1 block text-sm font-medium text-slate-700"
                                >
                                    Destination
                                </label>

                                <input
                                    type="text"
                                    placeholder="Ex : Toamasina"
                                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                />
                            </div>

                            <div>
                                <label
                                    class="mb-1 block text-sm font-medium text-slate-700"
                                >
                                    Date du voyage
                                </label>

                                <input
                                    type="date"
                                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                />
                            </div>

                            <button
                                type="button"
                                class="w-full rounded-lg bg-green-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-green-700"
                            >
                                Rechercher
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Ancre temporaire -->
        <!-- Pourquoi choisir Transport Mada -->
        <section id="apropos" class="bg-white py-20">
            <div class="mx-auto max-w-7xl px-6">
                <!-- Titre -->
                <div class="mx-auto max-w-2xl text-center">
                    <p
                        class="text-sm font-semibold uppercase tracking-wider text-green-600"
                    >
                        Pourquoi nous choisir ?
                    </p>

                    <h2
                        class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl"
                    >
                        Une réservation simple et pratique
                    </h2>

                    <p class="mt-4 text-lg text-slate-600">
                        Transport Mada vous accompagne pour organiser vos
                        déplacements facilement et en toute tranquillité.
                    </p>
                </div>

                <!-- Cartes -->
                <div class="mt-12 grid gap-8 md:grid-cols-3">
                    <!-- Carte 1 -->
                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                    >
                        <img
                            src="/images/trajets.jpg"
                            alt="Trajets disponibles"
                            class="h-48 w-full rounded-xl object-cover"
                        />

                        <h3 class="mt-6 text-xl font-bold text-slate-900">
                            Nombreux trajets
                        </h3>

                        <p class="mt-3 leading-7 text-slate-600">
                            Consultez les trajets disponibles et choisissez
                            facilement votre destination.
                        </p>
                    </div>

                    <!-- Carte 2 -->
                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                    >
                        <img
                            src="/images/reservations.jpg"
                            alt="Réservation sécurisée"
                            class="h-48 w-full rounded-xl object-cover"
                        />

                        <h3 class="mt-6 text-xl font-bold text-slate-900">
                            Réservation sécurisée
                        </h3>

                        <p class="mt-3 leading-7 text-slate-600">
                            Gérez vos réservations simplement avec un système
                            conçu pour faciliter vos déplacements.
                        </p>
                    </div>

                    <!-- Carte 3 -->
                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                    >
                        <img
                            src="/images/rapides.jpg"
                            alt="Réservation rapide"
                            class="h-48 w-full rounded-xl object-cover"
                        />

                        <h3 class="mt-6 text-xl font-bold text-slate-900">
                            Simple et rapide
                        </h3>

                        <p class="mt-3 leading-7 text-slate-600">
                            Recherchez votre trajet et effectuez votre
                            réservation en quelques étapes.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Ancre trajets -->
        <div id="trajets"></div>
    </div>
</template>
