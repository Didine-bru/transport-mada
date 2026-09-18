<script setup>
import { ref, watch } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { Plus, Pencil, Trash2, Save, CheckCircle } from "lucide-vue-next";

const props = defineProps({
    transporters: Array,
    flash: Object,
});

/*
Popup de succès
*/

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

/*
Modales
*/

const showCreateModal = ref(false);
const editingTransporter = ref(null);

const showDeleteModal = ref(false);
const deletingTransporter = ref(null);

/*
Formulaire
*/

const form = useForm({
    company_name: "",
    manager_name: "",
    cin: "",
    phone: "",
    email: "",
    address: "",
    is_active: true,
});

/*
| Ouvrir le formulaire d'ajout
*/

const openCreateModal = () => {
    editingTransporter.value = null;

    form.reset();

    form.is_active = true;

    showCreateModal.value = true;
};

/*
Modifier un transporteur
*/

const editTransporter = (transporter) => {
    editingTransporter.value = transporter;

    form.company_name = transporter.company_name;
    form.manager_name = transporter.manager_name;
    form.cin = transporter.cin;
    form.phone = transporter.phone;
    form.email = transporter.email ?? "";
    form.address = transporter.address ?? "";
    form.is_active = transporter.is_active;

    showCreateModal.value = true;
};

/*
Enregistrer / Modifier
*/

const submit = () => {
    if (editingTransporter.value) {
        form.put(route("transporters.update", editingTransporter.value.id), {
            onSuccess: () => {
                showCreateModal.value = false;
                editingTransporter.value = null;
                form.reset();
            },
        });

        return;
    }

    form.post(route("transporters.store"), {
        onSuccess: () => {
            showCreateModal.value = false;
            form.reset();
        },
    });
};

/*
Confirmation suppression
*/

const confirmDelete = (transporter) => {
    deletingTransporter.value = transporter;
    showDeleteModal.value = true;
};

/* 
Supprimer
*/

const deleteTransporter = () => {
    form.delete(route("transporters.destroy", deletingTransporter.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            deletingTransporter.value = null;
            form.reset();
        },
    });
};

/*
 Classe du statut
*/

const getStatusClass = (isActive) => {
    return isActive ? "bg-green-100 text-green-700" : "bg-red-100 text-red-700";
};
</script>

<template>
    <!-- 
         Popup de succès
    -->

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

    <Head title="Gestion des transporteurs" />

    <AuthenticatedLayout>
        <!-- 
             Header
         -->

        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Gestion des transporteurs
            </h2>
        </template>

        <!-- 
             Contenu
         -->

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Titre + bouton Ajouter -->

                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-bold">
                                    Transporteurs
                                </h3>

                                <p class="mt-1 text-gray-600">
                                    Liste des transporteurs enregistrés.
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

                        <!--
                             Tableau
                         -->

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr
                                        class="text-left text-sm font-semibold text-gray-700"
                                    >
                                        <th class="px-4 py-3">Entreprise</th>

                                        <th class="px-4 py-3">Responsable</th>

                                        <th class="px-4 py-3">CIN</th>

                                        <th class="px-4 py-3">Téléphone</th>

                                        <th class="px-4 py-3">Email</th>

                                        <th class="px-4 py-3">Adresse</th>

                                        <th class="px-4 py-3">Statut</th>

                                        <th class="px-4 py-3 text-right">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    <!-- Aucun transporteur -->

                                    <tr
                                        v-if="
                                            !transporters ||
                                            transporters.length === 0
                                        "
                                        class="text-sm"
                                    >
                                        <td
                                            colspan="8"
                                            class="px-4 py-8 text-center text-gray-500"
                                        >
                                            Aucun transporteur enregistré.
                                        </td>
                                    </tr>

                                    <!-- Liste -->

                                    <tr
                                        v-for="transporter in transporters"
                                        :key="transporter.id"
                                        class="text-sm"
                                    >
                                        <td class="px-4 py-3 font-medium">
                                            {{ transporter.company_name }}
                                        </td>

                                        <td class="px-4 py-3 text-gray-600">
                                            {{ transporter.manager_name }}
                                        </td>

                                        <td class="px-4 py-3 text-gray-600">
                                            {{ transporter.cin }}
                                        </td>

                                        <td class="px-4 py-3 text-gray-600">
                                            {{ transporter.phone }}
                                        </td>

                                        <td class="px-4 py-3 text-gray-600">
                                            {{ transporter.email || "—" }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">
                                            {{ transporter.address || "—" }}
                                        </td>

                                        <td class="px-4 py-3">
                                            <span
                                                class="rounded-full px-3 py-1 text-xs font-semibold"
                                                :class="
                                                    getStatusClass(
                                                        transporter.is_active,
                                                    )
                                                "
                                            >
                                                {{
                                                    transporter.is_active
                                                        ? "Actif"
                                                        : "Inactif"
                                                }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-3">
                                            <div
                                                class="flex items-center justify-end gap-2"
                                            >
                                                <!-- Modifier -->

                                                <button
                                                    type="button"
                                                    @click="
                                                        editTransporter(
                                                            transporter,
                                                        )
                                                    "
                                                    title="Modifier"
                                                    class="inline-flex items-center justify-center rounded-lg p-2 text-amber-600 hover:bg-amber-50"
                                                >
                                                    <Pencil :size="18" />
                                                </button>

                                                <!-- Supprimer -->

                                                <button
                                                    type="button"
                                                    @click="
                                                        confirmDelete(
                                                            transporter,
                                                        )
                                                    "
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

        <!--
             Modal Ajouter / Modifier
        -->

        <div
            v-if="showCreateModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
        >
            <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl">
                <!-- En-tête -->

                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900">
                        {{
                            editingTransporter
                                ? "Modifier le transporteur"
                                : "Ajouter un transporteur"
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

                <!-- Formulaire -->

                <form @submit.prevent="submit" class="mt-6 space-y-5">
                    <!-- Entreprise -->

                    <div>
                        <label
                            for="company_name"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Nom de l'entreprise
                        </label>

                        <input
                            id="company_name"
                            v-model="form.company_name"
                            type="text"
                            placeholder="Ex : Transport Mada"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        />

                        <p
                            v-if="form.errors.company_name"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.company_name }}
                        </p>
                    </div>

                    <!-- Responsable -->

                    <div>
                        <label
                            for="manager_name"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Responsable
                        </label>

                        <input
                            id="manager_name"
                            v-model="form.manager_name"
                            type="text"
                            placeholder="Nom complet du responsable"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        />

                        <p
                            v-if="form.errors.manager_name"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.manager_name }}
                        </p>
                    </div>

                    <!-- CIN -->

                    <div>
                        <label
                            for="cin"
                            class="block text-sm font-medium text-gray-700"
                        >
                            CIN
                        </label>

                        <input
                            id="cin"
                            v-model="form.cin"
                            type="text"
                            inputmode="numeric"
                            maxlength="12"
                            placeholder="12 chiffres"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        />

                        <p
                            v-if="form.errors.cin"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.cin }}
                        </p>
                    </div>

                    <!-- Téléphone -->

                    <div>
                        <label
                            for="phone"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Téléphone
                        </label>

                        <input
                            id="phone"
                            v-model="form.phone"
                            type="text"
                            placeholder="Ex : 034 00 000 00"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        />

                        <p
                            v-if="form.errors.phone"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.phone }}
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

                    <!-- Adresse -->

                    <div>
                        <label
                            for="address"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Adresse
                        </label>

                        <textarea
                            id="address"
                            v-model="form.address"
                            rows="3"
                            placeholder="Adresse du transporteur"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        ></textarea>

                        <p
                            v-if="form.errors.address"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.address }}
                        </p>
                    </div>

                    <!-- Statut -->

                    <div>
                        <label
                            for="is_active"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Statut
                        </label>

                        <select
                            id="is_active"
                            v-model="form.is_active"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option :value="true">Actif</option>

                            <option :value="false">Inactif</option>
                        </select>

                        <p
                            v-if="form.errors.is_active"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.is_active }}
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
                                        : editingTransporter
                                          ? "Modifier"
                                          : "Enregistrer"
                                }}
                            </span>
                        </button>
                    </div>
                </form>

                <!-- Fermer -->

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

        <!-- 
             Modal confirmation suppression
         -->

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
                        Supprimer le transporteur
                    </h3>
                </div>

                <!-- Message -->

                <div class="mt-5">
                    <p class="text-sm text-gray-600">
                        Voulez-vous vraiment supprimer
                        <span class="font-semibold text-gray-900">
                            {{ deletingTransporter?.company_name }}
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
                        @click="deleteTransporter"
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
