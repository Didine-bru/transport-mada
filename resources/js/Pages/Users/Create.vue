<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: 'client',
});

const submit = () => {
    form.post(route('users.store'));
};
</script>

<template>
    <Head title="Ajouter un utilisateur" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Ajouter un utilisateur
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">

                        <div class="mb-6">
                            <h3 class="text-2xl font-bold text-gray-900">
                                Nouvel utilisateur
                            </h3>

                            <p class="mt-1 text-gray-600">
                                Remplissez les informations du nouvel utilisateur.
                            </p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">

                            <!-- Nom -->
                            <div>
                                <label
                                    for="name"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Nom
                                </label>

                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Nom complet"
                                />

                                <p
                                    v-if="form.errors.name"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.name }}
                                </p>
                            </div>

                            <!-- Email -->
                            <div>
                                <label
                                    for="email"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Email
                                </label>

                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="exemple@email.com"
                                />

                                <p
                                    v-if="form.errors.email"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.email }}
                                </p>
                            </div>

                            <!-- Mot de passe -->
                            <div>
                                <label
                                    for="password"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Mot de passe
                                </label>

                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Minimum 8 caractères"
                                />

                                <p
                                    v-if="form.errors.password"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.password }}
                                </p>
                            </div>

                            <!-- Rôle -->
                            <div>
                                <label
                                    for="role"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Rôle
                                </label>

                                <select
                                    id="role"
                                    v-model="form.role"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="client">Client</option>
                                    <option value="agent">Agent</option>
                                    <option value="transporteur">Transporteur</option>
                                    <option value="admin">Administrateur</option>
                                </select>

                                <p
                                    v-if="form.errors.role"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.role }}
                                </p>
                            </div>

                            <!-- Boutons -->
                            <div class="flex items-center justify-end gap-3">

                                <Link
                                    :href="route('users.index')"
                                    class="inline-flex items-center rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-200"
                                >
                                    Annuler
                                </Link>

                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    {{ form.processing ? 'Enregistrement...' : 'Enregistrer' }}
                                </button>

                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>