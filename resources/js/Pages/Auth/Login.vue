<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

import { Mail, LockKeyhole, Eye, EyeOff, LogIn } from "lucide-vue-next";
import { ref } from "vue";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});
const showPassword = ref(false);

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />
        <div class="mb-7">
            <div
                class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600"
            >
                <LogIn :size="24" />
            </div>

            <h1 class="text-2xl font-bold tracking-tight text-gray-800">
                Bienvenue sur Transport Mada
            </h1>

            <p class="mt-2 text-sm leading-6 text-gray-500">
                Connectez-vous à votre espace pour gérer facilement vos
                réservations et vos trajets.
            </p>
        </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <label
                    for="email"
                    class="block text-sm font-medium text-gray-700"
                >
                    Adresse email
                </label>

                <div class="relative mt-2">
                    <Mail
                        :size="18"
                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                    />

                    <input
                        id="email"
                        type="email"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="votre@email.com"
                        class="w-full rounded-lg border border-gray-200 bg-white py-3 pl-10 pr-4 text-sm text-gray-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    />
                </div>

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-5">
                <label
                    for="password"
                    class="block text-sm font-medium text-gray-700"
                >
                    Mot de passe
                </label>

                <div class="relative mt-2">
                    <LockKeyhole
                        :size="18"
                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                    />

                    <input
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        placeholder="Votre mot de passe"
                        class="w-full rounded-lg border border-gray-200 bg-white py-3 pl-10 pr-11 text-sm text-gray-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    />

                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 transition hover:text-indigo-600"
                    >
                        <EyeOff v-if="showPassword" :size="18" />
                        <Eye v-else :size="18" />
                    </button>
                </div>

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-5">
                <label class="flex items-center cursor-pointer">
                    <Checkbox name="remember" v-model:checked="form.remember" />

                    <span class="ms-2 text-sm text-gray-600">
                        Se souvenir de moi
                    </span>
                </label>
            </div>

            <div class="mt-6 flex items-center justify-between gap-4">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm font-medium text-gray-500 transition hover:text-indigo-600"
                >
                    Mot de passe oublié ?
                </Link>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <LogIn :size="18" />
                    {{ form.processing ? "Connexion..." : "Se connecter" }}
                </button>
            </div>
        </form>
    </GuestLayout>
</template>
