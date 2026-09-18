<script setup>
import { ref, watch } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { Plus, Pencil, Trash2, Save, CheckCircle } from "lucide-vue-next";

const props = defineProps({
    users: Array,
    flash: Object,
});
const showSuccessPopup = ref(false);

watch(
    () => props.flash?.success,
    (message) => {
        if (message) {
            showSuccessPopup.value = true;

            setTimeout(() => {
                showSuccessPopup.value = false;
            }, 3000);
        }
    },
);
const showCreateModal = ref(false);
const editingUser = ref(null);

const showDeleteModal = ref(false);
const deletingUser = ref(null);

const form = useForm({
    name: "",
    email: "",
    password: "",
    role: "client",
});

const submit = () => {
    if (editingUser.value) {
        form.put(route("users.update", editingUser.value.id), {
            onSuccess: () => {
                showCreateModal.value = false;
                editingUser.value = null;
                form.reset();
            },
        });

        return;
    }

    form.post(route("users.store"), {
        onSuccess: () => {
            showCreateModal.value = false;
            form.reset();
        },
    });
};
const openCreateModal = () => {
    editingUser.value = null;

    form.reset();

    form.role = "client";

    showCreateModal.value = true;
};
const editUser = (user) => {
    editingUser.value = user;

    form.name = user.name;
    form.email = user.email;
    form.password = "";
    form.role = user.role;

    showCreateModal.value = true;
};
const confirmDelete = (user) => {
    //console.log("Utilisateur sélectionné :", user);

    deletingUser.value = user;
    showDeleteModal.value = true;

    //console.log("showDeleteModal :", showDeleteModal.value);
};
const deleteUser = () => {
    form.delete(route("users.destroy", deletingUser.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            deletingUser.value = null;
            form.reset();
        },
    });
};
const getRoleClass = (role) => {
    switch (role) {
        case "admin":
            return "bg-red-100 text-red-700";

        case "transporteur":
            return "bg-purple-100 text-purple-700";

        case "agent":
            return "bg-orange-100 text-orange-700";

        case "client":
            return "bg-green-100 text-green-700";

        default:
            return "bg-gray-100 text-gray-700";
    }
};
</script>

<template>
    <!-- Popup de succès -->
    <Transition
        enter-active-class="transform transition duration-300 ease-out"
        enter-from-class="translate-x-full opacity-0"
        enter-to-class="translate-x-0 opacity-100"
        leave-active-class="transform transition duration-200 ease-in"
        leave-from-class="translate-x-0 opacity-100"
        leave-to-class="translate-x-full opacity-0"
    >
        <div
            v-if="showSuccessPopup"
            class="fixed right-6 top-6 z-[100] flex w-full max-w-sm items-start gap-3 rounded-xl border border-green-200 bg-white p-4 shadow-lg"
        >
            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-100"
            >
                <CheckCircle :size="22" class="text-green-600" />
            </div>

            <div class="flex-1">
                <p class="font-semibold text-gray-900">Opération réussie</p>

                <p class="mt-1 text-sm text-gray-600">
                    {{ props.flash?.success }}
                </p>
            </div>

            <button
                type="button"
                @click="showSuccessPopup = false"
                class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
            >
                ✕
            </button>
        </div>
    </Transition>
    <Head title="Gestion des utilisateurs" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Gestion des utilisateurs
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-bold">Utilisateurs</h3>

                                <p class="mt-1 text-gray-600">
                                    Liste des utilisateurs enregistrés.
                                </p>
                            </div>

                            <button
                                type="button"
                                @click="openCreateModal"
                                class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-green-700"
                            >
                                <Plus :size="18" />
                                Ajouter
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr
                                        class="text-left text-sm font-semibold text-gray-700"
                                    >
                                        <th class="px-4 py-3">Nom</th>
                                        <th class="px-4 py-3">Email</th>
                                        <th class="px-4 py-3">Rôle</th>
                                        <th class="px-4 py-3">Date</th>
                                        <th class="px-4 py-3 text-right">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    <tr
                                        v-for="user in users"
                                        :key="user.id"
                                        class="text-sm"
                                    >
                                        <td class="px-4 py-3 font-medium">
                                            {{ user.name }}
                                        </td>

                                        <td class="px-4 py-3 text-gray-600">
                                            {{ user.email }}
                                        </td>

                                        <td class="px-4 py-3">
                                            <span
                                                class="rounded-full px-3 py-1 text-xs font-semibold"
                                                :class="getRoleClass(user.role)"
                                            >
                                                {{ user.role }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-3 text-gray-600">
                                            {{ user.created_at }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div
                                                class="flex items-center justify-end gap-2"
                                            >
                                                <button
                                                    type="button"
                                                    @click="editUser(user)"
                                                    title="Modifier"
                                                    class="inline-flex items-center justify-center rounded-lg p-2 text-amber-600 hover:bg-amber-50"
                                                >
                                                    <Pencil :size="18" />
                                                </button>

                                                <button
                                                    type="button"
                                                    @click="confirmDelete(user)"
                                                    title="Supprimer"
                                                    class="inline-flex items-center justify-center rounded-lg p-2 text-red-600 transition hover:bg-red-50"
                                                >
                                                    <Trash2 :size="18" />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Ajouter un utilisateur -->
        <div
            v-if="showCreateModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
        >
            <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900">
                        {{
                            editingUser
                                ? "Modifier l’utilisateur"
                                : "Ajouter un utilisateur"
                        }}
                    </h3>

                    <button
                        type="button"
                        @click="showCreateModal = false"
                        class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                    >
                        ✕
                    </button>
                </div>

                <form @submit.prevent="submit" class="mt-6 space-y-5">
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
                            placeholder="Nom complet"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
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
                            placeholder="exemple@email.com"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
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
                            placeholder="Minimum 8 caractères"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
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

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-2">
                        <button
                            type="button"
                            @click="showCreateModal = false"
                            class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-200"
                        >
                            Annuler
                        </button>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            title="Enregistrer"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-green-700 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <Save :size="18" />

                            <span>
                                {{
                                    form.processing
                                        ? "Enregistrement..."
                                        : editingUser
                                          ? "Modifier"
                                          : "Enregistrer"
                                }}
                            </span>
                        </button>
                    </div>
                </form>

                <div class="mt-6 flex justify-end">
                    <button
                        type="button"
                        @click="showCreateModal = false"
                        class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200"
                    >
                        Fermer
                    </button>
                </div>
            </div>
        </div>
        <!-- Modal de confirmation de suppression -->
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
        >
            <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
                <!-- En-tête -->
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100"
                    >
                        <Trash2 :size="20" class="text-red-600" />
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900">
                        Supprimer l'utilisateur
                    </h3>
                </div>

                <!-- Message -->
                <div class="mt-5">
                    <p class="text-sm text-gray-600">
                        Voulez-vous vraiment supprimer
                        <span class="font-semibold text-gray-900">
                            {{ deletingUser?.name }}
                        </span>
                        ?
                    </p>

                    <p class="mt-2 text-sm text-red-600">
                        Cette action est irréversible.
                    </p>
                </div>

                <!-- Boutons -->
                <div class="mt-6 flex justify-end gap-3">
                    <button
                        type="button"
                        @click="showDeleteModal = false"
                        class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-200"
                    >
                        Annuler
                    </button>

                    <button
                        type="button"
                        @click="deleteUser"
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-red-700"
                    >
                        <Trash2 :size="18" />

                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
