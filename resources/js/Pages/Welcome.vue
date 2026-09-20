<script setup>
import { computed, ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import { Calendar, Clock, Armchair, Bus, Search  } from "lucide-vue-next";


const departureCity = ref("");
const destinationCity = ref("");
const departureDate = ref("");
const filteredTrips = computed(() => {
    return props.trips.filter((trip) => {
        const departureMatch =
            !departureCity.value ||
            trip.departure_city
                .toLowerCase()
                .includes(departureCity.value.toLowerCase());

        const destinationMatch =
            !destinationCity.value ||
            trip.destination_city
                .toLowerCase()
                .includes(destinationCity.value.toLowerCase());

        const dateMatch =
            !departureDate.value ||
            trip.departure_date === departureDate.value;

        return departureMatch && destinationMatch && dateMatch;
    });
});
const searchTrips = () => {
    // La recherche sera appliquée dans filteredTrips()
};
const props = defineProps({
    auth: Object,
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    trips: {
        type: Array,
        default: () => [],
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
                <Link href="/" class="text-xl font-bold text-indigo-600">
                    Transport Mada
                </Link>

                <!-- Menu -->
                <div class="hidden items-center gap-8 md:flex">
                    <Link href="/" class="text-sm font-medium text-indigo-600">
                        Accueil
                    </Link>

                    <a
                        href="#trajets"
                        class="text-sm font-medium text-slate-600 transition hover:text-indigo-600"
                    >
                        Trajets
                    </a>

                    <a
                        href="#apropos"
                        class="text-sm font-medium text-slate-600 transition hover:text-indigo-600"
                    >
                        À propos
                    </a>

                    <Link
                        href="/login"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700"
                    >
                        Se connecter
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Hero -->
        <section
            class="relative overflow-hidden bg-gradient-to-br from-indigo-700 to-violet-700"
        >
            <div
                class="mx-auto max-w-7xl px-6 py-20 lg:flex lg:items-center lg:justify-between lg:gap-12"
            >
                <!-- Texte -->
                <div
                    class="max-w-2xl text-white animate-[fadeInLeft_0.8s_ease-out]"
                >
                    <p
                        class="mb-4 text-sm font-semibold uppercase tracking-wider text-indigo-100"
                    >
                        Votre voyage commence ici
                    </p>

                    <h1
                        class="text-4xl font-extrabold leading-tight sm:text-5xl lg:text-6xl"
                    >
                        Réservez votre voyage en toute simplicité
                    </h1>

                    <p class="mt-6 max-w-xl text-lg leading-8 text-indigo-50">
                        Trouvez facilement votre trajet, choisissez votre
                        transporteur et réservez vos places en quelques clics.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4">
                        <a
                            href="#trajets"
                            class="rounded-lg bg-white px-6 py-3 text-sm font-semibold text-indigo-700 shadow-sm transition hover:bg-indigo-50"
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
                <!-- Image du véhicule -->
                <div
                    class="mt-12 w-full max-w-lg lg:mt-0"
                    style="animation: fadeInRight 0.8s ease-out"
                >
                    <div class="relative">
                        <!-- Décoration derrière l'image -->
                        <div
                            class="absolute -inset-4 rounded-3xl bg-violet-400/20 blur-2xl"
                        ></div>

                        <!-- Image -->
                        <div
                            class="relative overflow-hidden rounded-3xl border border-white/20 bg-white/10 shadow-2xl"
                        >
                            <img
                                src="/images/voiture1.jpg"
                                alt="Transport en bus"
                                class="h-[360px] w-full object-cover transition duration-700 hover:scale-105"
                            />
                            <div
                                class="absolute bottom-5 left-5 rounded-xl bg-white px-4 py-3 shadow-xl"
                                style="animation: float 3s ease-in-out infinite"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-indigo-600"
                                    >
                                        ✓
                                    </div>

                                    <div>
                                        <p
                                            class="text-sm font-semibold text-slate-900"
                                        >
                                            Réservation simple
                                        </p>

                                        <p class="text-xs text-slate-500">
                                            Rapide et pratique
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Ancre temporaire -->
        <!-- Pourquoi choisir Transport Mada -->
        <section
            id="apropos"
            class="bg-white py-20"
            style="animation: fadeInUp 0.8s ease-out"
        >
            <div class="mx-auto max-w-7xl px-6">
                <!-- Titre -->
                <div class="mx-auto max-w-2xl text-center">
                    <p
                        class="text-sm font-semibold uppercase tracking-wider text-indigo-600"
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
        <section
            id="trajets"
            class="bg-slate-50 py-20"
            style="animation: fadeInUp 0.8s ease-out"
        >
            <div class="mx-auto max-w-7xl px-6">
                <div class="mx-auto max-w-2xl text-center">
                    <p
                        class="text-sm font-semibold uppercase tracking-wider text-indigo-600"
                    >
                        Nos trajets
                    </p>

                    <h2
                        class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl"
                    >
                        Trouvez votre prochain trajet
                    </h2>

                    <p class="mt-4 text-lg text-slate-600">
                        Recherchez facilement un trajet et préparez votre
                        voyage.
                    </p>
                </div>

                <div
                    class="mx-auto mt-10 max-w-4xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
                >
                    <div class="grid gap-5 md:grid-cols-3">
                        <div>
                            <label
                                class="mb-2 block text-sm font-medium text-slate-700"
                            >
                                Départ
                            </label>

                            <input
                                v-model="departureCity"
                                type="text"
                                placeholder="Ex : Antananarivo"
                                class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>

                        <div>
                            <label
                                class="mb-2 block text-sm font-medium text-slate-700"
                            >
                                Destination
                            </label>

                            <input
                                v-model="destinationCity"
                                type="text"
                                placeholder="Ex : Toamasina"
                                class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>

                        <div>
                            <label
                                class="mb-2 block text-sm font-medium text-slate-700"
                            >
                                Date du voyage
                            </label>

                            <input
                                v-model="departureDate"
                                type="date"
                                class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-center">
                        <button
                            type="button"
                            @click="searchTrips"
                            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                        >
                            <Search :size="18" />
                            Rechercher un trajet
                        </button>
                    </div>
                </div>
                <div class="mx-auto mt-12 max-w-6xl">
                    <h3 class="text-2xl font-bold text-slate-900">
                        Trajets disponibles
                    </h3>

                    <p class="mt-2 text-slate-600">
                        Découvrez les prochains trajets disponibles.
                    </p>

                    <div
                       v-if="filteredTrips.length > 0"
                        class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3"
                    >
                        <div
                            v-for="trip in filteredTrips"
                            :key="trip.id"
                            class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                        >
                            <div class="flex items-center justify-between">
                                <span
                                    class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700"
                                >
                                    Disponible
                                </span>

                                <span class="text-lg font-bold text-slate-900">
                                    {{
                                        Number(trip.price).toLocaleString(
                                            "fr-FR",
                                        )
                                    }}
                                    Ar
                                </span>
                            </div>

                            <div class="mt-5">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 text-indigo-600"
                                    >
                                       <Bus :size="20" />
                                    </div>

                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ trip.departure_city }}
                                        </p>

                                        <p class="text-sm text-slate-500">
                                            vers {{ trip.destination_city }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 space-y-2 text-sm text-slate-600">
                                <p class="flex items-center gap-2 text-sm text-slate-600">
                                    <Calendar :size="17" />
                                    {{
                                        new Date(
                                            trip.departure_date,
                                        ).toLocaleDateString("fr-FR")
                                    }}
                                </p>

                                <p class="flex items-center gap-2 text-sm text-slate-600">
                                    <Clock :size="17" />
                                     {{ trip.departure_time }}</p>

                                <p class="flex items-center gap-2 text-sm text-slate-600">
                                    <Armchair :size="17" /> {{ trip.available_seats }} place(s)
                                    disponible(s)
                                </p>
                            </div>

                            <div class="mt-6">
                                <Link
                                    href="/login"
                                    class="block w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-indigo-700"
                                >
                                    Réserver
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div
                        v-else
                        class="mt-6 rounded-xl border border-slate-200 bg-white p-8 text-center"
                    >
                        <p class="text-slate-500">
                            Aucun trajet disponible pour le moment.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
