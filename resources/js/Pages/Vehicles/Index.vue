<script setup>
import { ref, watch } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import {
    Plus,
    Pencil,
    Trash2,
    Save,
    CheckCircle,
} from "lucide-vue-next";

const props = defineProps({
    vehicles: Array,
    transporters: Array,
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
const editingVehicle = ref(null);

const showDeleteModal = ref(false);
const deletingVehicle = ref(null);

const form = useForm({
    transporter_id: "",
    registration_number: "",
    brand: "",
    model: "",
    seats: "",
    year: "",
    is_active: true,
});

const openCreateModal = () => {
    editingVehicle.value = null;

    form.reset();

    form.transporter_id = "";
    form.is_active = true;

    showCreateModal.value = true;
};

const editVehicle = (vehicle) => {
    editingVehicle.value = vehicle;

    form.transporter_id = vehicle.transporter_id;
    form.registration_number = vehicle.registration_number;
    form.brand = vehicle.brand;
    form.model = vehicle.model;
    form.seats = vehicle.seats;
    form.year = vehicle.year ?? "";
    form.is_active = vehicle.is_active;

    showCreateModal.value = true;
};

const submit = () => {
    if (editingVehicle.value) {
        form.put(
            route("vehicles.update", editingVehicle.value.id),
            {
                onSuccess: () => {
                    showCreateModal.value = false;
                    editingVehicle.value = null;
                    form.reset();
                },
            },
        );

        return;
    }

    form.post(route("vehicles.store"), {
        onSuccess: () => {
            showCreateModal.value = false;
            form.reset();
        },
    });
};

const confirmDelete = (vehicle) => {
    deletingVehicle.value = vehicle;
    showDeleteModal.value = true;
};

const deleteVehicle = () => {
    form.delete(
        route("vehicles.destroy", deletingVehicle.value.id),
        {
            onSuccess: () => {
                showDeleteModal.value = false;
                deletingVehicle.value = null;
                form.reset();
            },
        },
    );
};

const getStatusClass = (isActive) => {
    return isActive
        ? "bg-green-100 text-green-700"
        : "bg-red-100 text-red-700";
};
</script>

<template>
    <!-- Notification de succès -->
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
                <p class="font-semibold text-gray-900">
                    Opération réussie
                </p>

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

    <Head title="Gestion des véhicules" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Gestion des véhicules
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">

                        <!-- En-tête -->
                        <div
                            class="mb-6 flex items-center justify-between"
                        >
                            <div>
                                <h3 class="text-2xl font-bold">
                                    Véhicules
                                </h3>

                                <p class="mt-1 text-gray-600">
                                    Liste des véhicules enregistrés.
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

                        <!-- Tableau -->
                        <div class="overflow-x-auto">
                            <table
                                class="min-w-full divide-y divide-gray-200"
                            >
                                <thead>
                                    <tr
                                        class="text-left text-sm font-semibold text-gray-700"
                                    >
                                        <th class="px-4 py-3">
                                            Transporteur
                                        </th>

                                        <th class="px-4 py-3">
                                            Immatriculation
                                        </th>

                                        <th class="px-4 py-3">
                                            Marque
                                        </th>

                                        <th class="px-4 py-3">
                                            Modèle
                                        </th>

                                        <th class="px-4 py-3">
                                            Places
                                        </th>

                                        <th class="px-4 py-3">
                                            Année
                                        </th>

                                        <th class="px-4 py-3">
                                            Statut
                                        </th>

                                        <th
                                            class="px-4 py-3 text-right"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>

                                <tbody
                                    class="divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="vehicle in vehicles"
                                        :key="vehicle.id"
                                        class="text-sm"
                                    >
                                        <td
                                            class="px-4 py-3 font-medium"
                                        >
                                            {{ vehicle.transporter?.manager_name }} — {{ vehicle.transporter?.company_name }}
                                        </td>

                                        <td
                                            class="px-4 py-3 font-medium"
                                        >
                                            {{
                                                vehicle.registration_number
                                            }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ vehicle.brand }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ vehicle.model }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ vehicle.seats }}
                                        </td>

                                        <td
                                            class="px-4 py-3 text-gray-600"
                                        >
                                            {{ vehicle.year || "—" }}
                                        </td>

                                        <td class="px-4 py-3">
                                            <span
                                                class="rounded-full px-3 py-1 text-xs font-semibold"
                                                :class="
                                                    getStatusClass(
                                                        vehicle.is_active,
                                                    )
                                                "
                                            >
                                                {{
                                                    vehicle.is_active
                                                        ? "Actif"
                                                        : "Inactif"
                                                }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-3">
                                            <div
                                                class="flex items-center justify-end gap-2"
                                            >
                                                <button
                                                    type="button"
                                                    @click="
                                                        editVehicle(
                                                            vehicle,
                                                        )
                                                    "
                                                    title="Modifier"
                                                    class="inline-flex items-center justify-center rounded-lg p-2 text-amber-600 hover:bg-amber-50"
                                                >
                                                    <Pencil
                                                        :size="18"
                                                    />
                                                </button>

                                                <button
                                                    type="button"
                                                    @click="
                                                        confirmDelete(
                                                            vehicle,
                                                        )
                                                    "
                                                    title="Supprimer"
                                                    class="inline-flex items-center justify-center rounded-lg p-2 text-red-600 transition hover:bg-red-50"
                                                >
                                                    <Trash2
                                                        :size="18"
                                                    />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr v-if="vehicles.length === 0">
                                        <td
                                            colspan="8"
                                            class="px-4 py-8 text-center text-gray-500"
                                        >
                                            Aucun véhicule enregistré.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Ajouter / Modifier -->
        <div
            v-if="showCreateModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
        >
            <div
                class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl"
            >
                <div
                    class="flex items-center justify-between"
                >
                    <h3
                        class="text-xl font-semibold text-gray-900"
                    >
                        {{
                            editingVehicle
                                ? "Modifier le véhicule"
                                : "Ajouter un véhicule"
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

                <form
                    @submit.prevent="submit"
                    class="mt-6 space-y-5"
                >
                    <!-- Transporteur -->
                    <div>
                        <label
                            for="transporter_id"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Transporteur
                        </label>

                        <select
                            id="transporter_id"
                            v-model="form.transporter_id"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">
                                Sélectionner un transporteur
                            </option>

                            <option
                                v-for="transporter in transporters"
                                :key="transporter.id"
                                :value="transporter.id"
                            >
                                {{ transporter.manager_name }} - {{ transporter.company_name }}
                            </option>
                        </select>

                        <p
                            v-if="form.errors.transporter_id"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.transporter_id }}
                        </p>
                    </div>

                    <!-- Immatriculation -->
                    <div>
                        <label
                            for="registration_number"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Immatriculation
                        </label>

                        <input
                            id="registration_number"
                            v-model="form.registration_number"
                            type="text"
                            placeholder="Ex : 1234 TAA"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        />

                        <p
                            v-if="form.errors.registration_number"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.registration_number }}
                        </p>
                    </div>

                    <!-- Marque -->
                    <div>
                        <label
                            for="brand"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Marque
                        </label>

                        <input
                            id="brand"
                            v-model="form.brand"
                            type="text"
                            placeholder="Ex : Toyota"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        />

                        <p
                            v-if="form.errors.brand"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.brand }}
                        </p>
                    </div>

                    <!-- Modèle -->
                    <div>
                        <label
                            for="model"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Modèle
                        </label>

                        <input
                            id="model"
                            v-model="form.model"
                            type="text"
                            placeholder="Ex : Hiace"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        />

                        <p
                            v-if="form.errors.model"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.model }}
                        </p>
                    </div>

                    <!-- Places et année -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                for="seats"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Nombre de places
                            </label>

                            <input
                                id="seats"
                                v-model="form.seats"
                                type="number"
                                min="1"
                                placeholder="Ex : 18"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />

                            <p
                                v-if="form.errors.seats"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.seats }}
                            </p>
                        </div>

                        <div>
                            <label
                                for="year"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Année
                            </label>

                            <input
                                id="year"
                                v-model="form.year"
                                type="number"
                                min="1900"
                                placeholder="Ex : 2024"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />

                            <p
                                v-if="form.errors.year"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.year }}
                            </p>
                        </div>
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
                            <option :value="true">
                                Actif
                            </option>

                            <option :value="false">
                                Inactif
                            </option>
                        </select>

                        <p
                            v-if="form.errors.is_active"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.is_active }}
                        </p>
                    </div>

                    <!-- Boutons -->
                    <div
                        class="flex justify-end gap-3 pt-2"
                    >
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
                                        : editingVehicle
                                          ? "Modifier"
                                          : "Enregistrer"
                                }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal confirmation suppression -->
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
        >
            <div
                class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100"
                    >
                        <Trash2
                            :size="20"
                            class="text-red-600"
                        />
                    </div>

                    <h3
                        class="text-xl font-semibold text-gray-900"
                    >
                        Supprimer le véhicule
                    </h3>
                </div>

                <div class="mt-5">
                    <p class="text-sm text-gray-600">
                        Voulez-vous vraiment supprimer
                        <span
                            class="font-semibold text-gray-900"
                        >
                            {{
                                deletingVehicle?.registration_number
                            }}
                        </span>
                        ?
                    </p>

                    <p
                        class="mt-2 text-sm text-red-600"
                    >
                        Cette action est irréversible.
                    </p>
                </div>

                <div
                    class="mt-6 flex justify-end gap-3"
                >
                    <button
                        type="button"
                        @click="showDeleteModal = false"
                        class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-200"
                    >
                        Annuler
                    </button>

                    <button
                        type="button"
                        @click="deleteVehicle"
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