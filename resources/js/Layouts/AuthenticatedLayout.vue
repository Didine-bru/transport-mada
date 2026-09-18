<script setup>
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="relative min-h-screen bg-slate-50">
        <!-- ARRIÈRE-PLAN DÉCORATIF -->

        <!-- Halo bleu -->
        <div
            class="pointer-events-none fixed -left-24 top-40 z-0 h-80 w-80 rounded-full bg-blue-300/40 blur-3xl"
        ></div>

        <!-- Halo vert -->
        <div
            class="pointer-events-none fixed -right-24 top-32 z-0 h-96 w-96 rounded-full bg-emerald-300/30 blur-3xl"
        ></div>

        <!-- Halo violet -->
        <div
            class="pointer-events-none fixed bottom-0 left-1/3 z-0 h-80 w-80 rounded-full bg-violet-300/30 blur-3xl"
        ></div>

        <!-- NAVIGATION -->

        <nav class="relative border-b border-gray-100 bg-white">
            <!-- Primary Navigation Menu -->
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <!-- Partie gauche -->
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex shrink-0 items-center">
                            <Link :href="route('dashboard')">
                                <ApplicationLogo
                                    class="block h-9 w-auto fill-current text-gray-800"
                                />
                            </Link>
                        </div>

                        <!-- Navigation Links -->
                        <div
                            class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                        >
                            <!-- Dashboard -->
                            <NavLink
                                :href="route('dashboard')"
                                :active="route().current('dashboard')"
                            >
                                Dashboard
                            </NavLink>

                            <!-- Utilisateurs -->
                            <NavLink
                                v-if="page.props.auth.user.role === 'admin'"
                                :href="route('users.index')"
                                :active="route().current('users.*')"
                            >
                                Utilisateurs
                            </NavLink>

                            <!-- Transporteurs -->
                            <NavLink
                                v-if="page.props.auth.user.role === 'admin'"
                                :href="route('transporters.index')"
                                :active="route().current('transporters.*')"
                            >
                                Transporteurs
                            </NavLink>

                            <!-- Véhicules -->
                            <NavLink
                                v-if="page.props.auth.user.role === 'admin'"
                                :href="route('vehicles.index')"
                                :active="route().current('vehicles.*')"
                            >
                                Véhicules
                            </NavLink>
                            <!-- Trajets -->
                            <NavLink
                                v-if="page.props.auth.user.role === 'admin'"
                                :href="route('trips.index')"
                                :active="route().current('trips.*')"
                            >
                                Trajets
                            </NavLink>
                            <NavLink
                                v-if="page.props.auth.user.role === 'admin'"
                                :href="route('reservations.index')"
                                :active="route().current('reservations.*')"
                            >
                                Réservations
                            </NavLink>
                        </div>
                    </div>

                    <!-- PROFIL DESKTOP -->

                    <div class="hidden sm:ms-6 sm:flex sm:items-center">
                        <div class="relative ms-3">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                                        >
                                            {{ page.props.auth.user.name }}

                                            <svg
                                                class="-me-0.5 ms-2 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')">
                                        Profile
                                    </DropdownLink>

                                    <DropdownLink
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                    >
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <!-- MENU MOBILE -->

                    <div class="-me-2 flex items-center sm:hidden">
                        <button
                            @click="
                                showingNavigationDropdown =
                                    !showingNavigationDropdown
                            "
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                        >
                            <!-- Menu hamburger -->
                            <svg
                                class="h-6 w-6"
                                stroke="currentColor"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex':
                                            !showingNavigationDropdown,
                                    }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />

                                <!-- X -->
                                <path
                                    :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex':
                                            showingNavigationDropdown,
                                    }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- NAVIGATION MOBILE -->

            <div
                :class="{
                    block: showingNavigationDropdown,
                    hidden: !showingNavigationDropdown,
                }"
                class="relative z-20 sm:hidden"
            >
                <div class="space-y-1 pb-3 pt-2">
                    <!-- Dashboard -->
                    <ResponsiveNavLink
                        :href="route('dashboard')"
                        :active="route().current('dashboard')"
                    >
                        Dashboard
                    </ResponsiveNavLink>

                    <!-- Utilisateurs -->
                    <ResponsiveNavLink
                        v-if="page.props.auth.user.role === 'admin'"
                        :href="route('users.index')"
                        :active="route().current('users.*')"
                    >
                        Utilisateurs
                    </ResponsiveNavLink>

                    <!-- Transporteurs -->
                    <ResponsiveNavLink
                        v-if="page.props.auth.user.role === 'admin'"
                        :href="route('transporters.index')"
                        :active="route().current('transporters.*')"
                    >
                        Transporteurs
                    </ResponsiveNavLink>

                    <!-- Véhicules -->
                    <ResponsiveNavLink
                        v-if="page.props.auth.user.role === 'admin'"
                        :href="route('vehicles.index')"
                        :active="route().current('vehicles.*')"
                    >
                        Véhicules
                    </ResponsiveNavLink>
                    <ResponsiveNavLink
                        v-if="page.props.auth.user.role === 'admin'"
                        :href="route('trips.index')"
                        :active="route().current('trips.*')"
                    >
                        Trajets
                    </ResponsiveNavLink>
                    <ResponsiveNavLink
                        v-if="page.props.auth.user.role === 'admin'"
                        :href="route('reservations.index')"
                        :active="route().current('reservations.*')"
                    >
                        Réservations
                    </ResponsiveNavLink>
                </div>

                <!-- SETTINGS MOBILE -->

                <div class="border-t border-gray-200 pb-1 pt-4">
                    <div class="px-4">
                        <div class="text-base font-medium text-gray-800">
                            {{ page.props.auth.user.name }}
                        </div>

                        <div class="text-sm font-medium text-gray-500">
                            {{ page.props.auth.user.email }}
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <!-- Profile -->
                        <ResponsiveNavLink :href="route('profile.edit')">
                            Profile
                        </ResponsiveNavLink>

                        <!-- Logout -->
                        <ResponsiveNavLink
                            :href="route('logout')"
                            method="post"
                            as="button"
                        >
                            Log Out
                        </ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <!-- PAGE HEADER -->

        <header v-if="$slots.header" class="relative z-20 bg-white/90 shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- PAGE CONTENT -->

        <main class="relative">
            <slot />
        </main>
    </div>
</template>
