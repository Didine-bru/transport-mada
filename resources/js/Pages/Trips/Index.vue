<script setup>
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { Plus, Pencil, Trash2, X, CheckCircle } from "lucide-vue-next";

const props = defineProps({
    trips: {
        type: Array,
        default: () => [],
    },

    transporters: {
        type: Array,
        default: () => [],
    },

    vehicles: {
        type: Array,
        default: () => [],
    },
});
/*
Popup de succès
*/
const showSuccessPopup = ref(false);
const successMessage = ref("");

const showSuccess = (message) => {
    successMessage.value = message;
    showSuccessPopup.value = true;

    setTimeout(() => {
        showSuccessPopup.value = false;
    }, 3000);
};

/*
|--------------------------------------------------------------------------
| Formulaire d'ajout
|--------------------------------------------------------------------------
*/

const form = useForm({
    transporter_id: "",
    vehicle_id: "",
    departure_city: "",
    destination_city: "",
    departure_date: "",
    departure_time: "",
    price: "",
    available_seats: "",
    status: "scheduled",
});

/*
|--------------------------------------------------------------------------
| Ajouter un trajet
|--------------------------------------------------------------------------
*/

const submit = () => {
    form.post(route("trips.store"), {
        onSuccess: () => {
            form.reset();
            form.status = "scheduled";

            showSuccess("Trajet créé avec succès.");
        },
    });
};

/*
|--------------------------------------------------------------------------
| Formulaire de modification
|--------------------------------------------------------------------------
*/

const editForm = useForm({
    transporter_id: "",
    vehicle_id: "",
    departure_city: "",
    destination_city: "",
    departure_date: "",
    departure_time: "",
    price: "",
    available_seats: "",
    status: "scheduled",
});

const editingTrip = ref(null);

const editTrip = (trip) => {
    editingTrip.value = trip;

    editForm.transporter_id = trip.transporter_id;
    editForm.vehicle_id = trip.vehicle_id;
    editForm.departure_city = trip.departure_city;
    editForm.destination_city = trip.destination_city;
    editForm.departure_date = trip.departure_date;
    editForm.departure_time = trip.departure_time;
    editForm.price = trip.price;
    editForm.available_seats = trip.available_seats;
    editForm.status = trip.status;
};

const updateTrip = () => {
    if (!editingTrip.value) {
        return;
    }

    editForm.put(route("trips.update", editingTrip.value.id), {
        onSuccess: () => {
            editingTrip.value = null;

            editForm.reset();
            editForm.status = "scheduled";

            showSuccess("Trajet modifié avec succès.");
        },
    });
};

const cancelEdit = () => {
    editingTrip.value = null;

    editForm.reset();
    editForm.status = "scheduled";
};

/*
|--------------------------------------------------------------------------
| Suppression
|--------------------------------------------------------------------------
*/

const deletingTrip = ref(null);

const deleteTrip = (trip) => {
    deletingTrip.value = trip;
};

const confirmDeleteTrip = () => {
    if (!deletingTrip.value) {
        return;
    }

    const tripId = deletingTrip.value.id;

    const deleteForm = useForm({});

    deleteForm.delete(route("trips.destroy", tripId), {
        onSuccess: () => {
            deletingTrip.value = null;

            showSuccess("Trajet supprimé avec succès.");
        },
    });
};
</script>

<template>
    <!-- 
         Popup de succès
    -->
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="translate-x-full opacity-0"
        enter-to-class="translate-x-0 opacity-100"
        leave-active-class="transition duration-300 ease-in"
        leave-from-class="translate-x-0 opacity-100"
        leave-to-class="translate-x-full opacity-0"
    >
        <div
            v-if="showSuccessPopup"
            class="fixed right-6 top-6 z-[100] flex w-full max-w-sm items-start gap-3 rounded-xl border border-green-200 bg-white p-4 shadow-lg"
        >
            <!-- Cercle de succès -->
            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-100"
            >
                <CheckCircle :size="22" class="text-green-600" />
            </div>

            <!-- Message -->
            <div class="flex-1">
                <p class="font-semibold text-gray-900">Opération réussie</p>

                <p class="mt-1 text-sm text-gray-600">
                    {{ successMessage }}
                </p>
            </div>

            <!-- Fermer -->
            <button
                type="button"
                @click="showSuccessPopup = false"
                class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
            >
                <X :size="18" />
            </button>
        </div>
    </Transition>

    <Head title="Trajets" />
    <AuthenticatedLayout>
        <!-- MODAL DE MODIFICATION -->
        <div
            v-if="editingTrip"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4"
        >
            <div class="w-full max-w-3xl rounded-2xl bg-white p-6 shadow-2xl">
                <!-- En-tête -->
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Modifier le trajet
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Modifiez les informations du trajet.
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="cancelEdit"
                        class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                    >
                        <X class="h-5 w-5" />
                    </button>
                </div>

                <!-- Formulaire -->
                <form @submit.prevent="updateTrip" class="space-y-5">
                    <!-- Transporteur + Véhicule -->
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Transporteur
                            </label>

                            <select
                                v-model="editForm.transporter_id"
                                class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">
                                    Sélectionner un transporteur
                                </option>

                                <option
                                    v-for="transporter in transporters"
                                    :key="transporter.id"
                                    :value="transporter.id"
                                >
                                    {{ transporter.manager_name }}
                                    — {{ transporter.company_name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Véhicule
                            </label>

                            <select
                                v-model="editForm.vehicle_id"
                                class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">
                                    Sélectionner un véhicule
                                </option>

                                <option
                                    v-for="vehicle in vehicles"
                                    :key="vehicle.id"
                                    :value="vehicle.id"
                                >
                                    {{ vehicle.registration_number }}
                                    — {{ vehicle.brand }}
                                    {{ vehicle.model }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Départ + Destination -->
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Ville de départ
                            </label>

                            <input
                                v-model="editForm.departure_city"
                                type="text"
                                class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>

                        <div>
                            <label
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Destination
                            </label>

                            <input
                                v-model="editForm.destination_city"
                                type="text"
                                class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                    </div>

                    <!-- Date + Heure -->
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Date de départ
                            </label>

                            <input
                                v-model="editForm.departure_date"
                                type="date"
                                class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>

                        <div>
                            <label
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Heure de départ
                            </label>

                            <input
                                v-model="editForm.departure_time"
                                type="time"
                                class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                    </div>

                    <!-- Prix + Places + Statut -->
                    <div class="grid gap-4 md:grid-cols-4">
                        <div>
                            <label
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Prix
                            </label>

                            <input
                                v-model="editForm.price"
                                type="number"
                                min="0"
                                step="0.01"
                                class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>

                        <div>
                            <label
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Places
                            </label>

                            <input
                                v-model="editForm.available_seats"
                                type="number"
                                min="1"
                                class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>

                        <div class="md:col-span-2">
                            <label
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Statut
                            </label>

                            <select
                                v-model="editForm.status"
                                class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="scheduled">Programmé</option>

                                <option value="cancelled">Annulé</option>

                                <option value="completed">Terminé</option>
                            </select>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div
                        class="flex justify-end gap-3 border-t border-gray-100 pt-4"
                    >
                        <button
                            type="button"
                            @click="cancelEdit"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                        >
                            Annuler
                        </button>

                        <button
                            type="submit"
                            :disabled="editForm.processing"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            {{
                                editForm.processing
                                    ? "Enregistrement..."
                                    : "Enregistrer les modifications"
                            }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- MODAL DE SUPPRESSION -->
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="deletingTrip"
                class="fixed inset-0 z-[60] flex items-center justify-center bg-black/40 px-4"
            >
                <div
                    class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl"
                >
                    <!-- Cercle suppression -->
                    <div class="flex justify-center">
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-full bg-red-100"
                        >
                            <Trash2 class="h-7 w-7 text-red-600" />
                        </div>
                    </div>

                    <!-- Texte -->
                    <div class="mt-5 text-center">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Supprimer ce trajet ?
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-gray-500">
                            Voulez-vous vraiment supprimer le trajet
                            <span class="font-semibold text-gray-700">
                                {{ deletingTrip.departure_city }}
                                →
                                {{ deletingTrip.destination_city }}
                            </span>
                            ?
                        </p>

                        <p class="mt-2 text-xs text-gray-400">
                            Cette action est définitive.
                        </p>
                    </div>

                    <!-- Boutons -->
                    <div class="mt-6 flex justify-center gap-3">
                        <button
                            type="button"
                            @click="deletingTrip = null"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                        >
                            Annuler
                        </button>

                        <button
                            type="button"
                            @click="confirmDeleteTrip"
                            class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-red-700"
                        >
                            <Trash2 class="h-4 w-4" />

                            Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </Transition>

        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Gestion des trajets
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="space-y-6">
                    <!-- FORMULAIRE -->
                    <div class="rounded-2xl bg-white p-6 shadow-sm">
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Ajouter un trajet
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Enregistrez un nouveau départ.
                            </p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-5">
                            <!-- Transporteur + Véhicule -->
                            <div class="grid gap-4 md:grid-cols-2">
                                <!-- Transporteur -->
                                <div>
                                    <label
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Transporteur
                                    </label>

                                    <select
                                        v-model="form.transporter_id"
                                        class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                        <option value="">
                                            Sélectionner un transporteur
                                        </option>

                                        <option
                                            v-for="transporter in transporters"
                                            :key="transporter.id"
                                            :value="transporter.id"
                                        >
                                            {{ transporter.manager_name }}
                                            — {{ transporter.company_name }}
                                        </option>
                                    </select>

                                    <p
                                        v-if="form.errors.transporter_id"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ form.errors.transporter_id }}
                                    </p>
                                </div>

                                <!-- Véhicule -->
                                <div>
                                    <label
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Véhicule
                                    </label>

                                    <select
                                        v-model="form.vehicle_id"
                                        class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                        <option value="">
                                            Sélectionner un véhicule
                                        </option>

                                        <option
                                            v-for="vehicle in vehicles"
                                            :key="vehicle.id"
                                            :value="vehicle.id"
                                        >
                                            {{ vehicle.registration_number }}
                                            — {{ vehicle.brand }}
                                            {{ vehicle.model }}
                                        </option>
                                    </select>

                                    <p
                                        v-if="form.errors.vehicle_id"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ form.errors.vehicle_id }}
                                    </p>
                                </div>
                            </div>

                            <!-- Départ + Destination -->
                            <div class="grid gap-4 md:grid-cols-2">
                                <!-- Ville de départ -->
                                <div>
                                    <label
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Ville de départ
                                    </label>

                                    <input
                                        v-model="form.departure_city"
                                        type="text"
                                        placeholder="Ex : Antananarivo"
                                        class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    />

                                    <p
                                        v-if="form.errors.departure_city"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ form.errors.departure_city }}
                                    </p>
                                </div>

                                <!-- Destination -->
                                <div>
                                    <label
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Destination
                                    </label>

                                    <input
                                        v-model="form.destination_city"
                                        type="text"
                                        placeholder="Ex : Toamasina"
                                        class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    />

                                    <p
                                        v-if="form.errors.destination_city"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ form.errors.destination_city }}
                                    </p>
                                </div>
                            </div>

                            <!-- Date + Heure -->
                            <div class="grid gap-4 md:grid-cols-2">
                                <!-- Date -->
                                <div>
                                    <label
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Date de départ
                                    </label>

                                    <input
                                        v-model="form.departure_date"
                                        type="date"
                                        class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>

                                <!-- Heure -->
                                <div>
                                    <label
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Heure de départ
                                    </label>

                                    <input
                                        v-model="form.departure_time"
                                        type="time"
                                        class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                            </div>

                            <!-- Prix + Places + Statut -->
                            <div class="grid gap-4 md:grid-cols-4">
                                <!-- Prix -->
                                <div class="md:col-span-1">
                                    <label
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Prix par place
                                    </label>

                                    <input
                                        v-model="form.price"
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        placeholder="Ex : 25000"
                                        class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>

                                <!-- Places -->
                                <div class="md:col-span-1">
                                    <label
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Places disponibles
                                    </label>

                                    <input
                                        v-model="form.available_seats"
                                        type="number"
                                        min="1"
                                        placeholder="Ex : 18"
                                        class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>

                                <!-- Statut -->
                                <div class="md:col-span-2">
                                    <label
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Statut
                                    </label>

                                    <select
                                        v-model="form.status"
                                        class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                        <option value="scheduled">
                                            Programmé
                                        </option>

                                        <option value="cancelled">
                                            Annulé
                                        </option>

                                        <option value="completed">
                                            Terminé
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Bouton -->
                            <div
                                class="flex justify-end border-t border-gray-100 pt-4"
                            >
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition duration-200 hover:bg-green-700 hover:shadow-md disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <Plus v-if="!form.processing" :size="18" />

                                    {{
                                        form.processing
                                            ? "Enregistrement..."
                                            : "Ajouter le trajet"
                                    }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- LISTE DES TRAJETS -->
                    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
                        <div class="border-b border-gray-100 px-6 py-5">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Liste des trajets
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Retrouvez ici tous les trajets enregistrés.
                            </p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                        >
                                            Trajet
                                        </th>

                                        <th
                                            class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                        >
                                            Transporteur
                                        </th>

                                        <th
                                            class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                        >
                                            Véhicule
                                        </th>

                                        <th
                                            class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                        >
                                            Départ
                                        </th>

                                        <th
                                            class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                        >
                                            Prix
                                        </th>

                                        <th
                                            class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                        >
                                            Places
                                        </th>

                                        <th
                                            class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                        >
                                            Statut
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>

                                <tbody
                                    class="divide-y divide-gray-100 bg-white"
                                >
                                    <tr
                                        v-for="trip in trips"
                                        :key="trip.id"
                                        class="transition hover:bg-gray-50"
                                    >
                                        <!-- Trajet -->
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div
                                                class="font-medium text-gray-900"
                                            >
                                                {{ trip.departure_city }}
                                                →
                                                {{ trip.destination_city }}
                                            </div>
                                        </td>

                                        <!-- Transporteur -->
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{
                                                    trip.transporter
                                                        ?.company_name
                                                }}
                                            </div>

                                            <div class="text-xs text-gray-500">
                                                {{
                                                    trip.transporter
                                                        ?.manager_name
                                                }}
                                            </div>
                                        </td>

                                        <!-- Véhicule -->
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{
                                                    trip.vehicle
                                                        ?.registration_number
                                                }}
                                            </div>

                                            <div class="text-xs text-gray-500">
                                                {{ trip.vehicle?.brand }}
                                                {{ trip.vehicle?.model }}
                                            </div>
                                        </td>

                                        <!-- Date / Heure -->
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{
                                                    new Date(
                                                        trip.departure_date,
                                                    ).toLocaleDateString(
                                                        "fr-FR",
                                                    )
                                                }}
                                            </div>

                                            <div class="text-xs text-gray-500">
                                                {{ trip.departure_time }}
                                            </div>
                                        </td>

                                        <!-- Prix -->
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span
                                                class="font-medium text-gray-900"
                                            >
                                                {{ trip.price }} Ar
                                            </span>
                                        </td>

                                        <!-- Places -->
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span
                                                class="font-medium text-gray-900"
                                            >
                                                {{ trip.available_seats }}
                                            </span>
                                        </td>

                                        <!-- Statut -->
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span
                                                v-if="
                                                    trip.status === 'scheduled'
                                                "
                                                class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700"
                                            >
                                                Programmé
                                            </span>

                                            <span
                                                v-else-if="
                                                    trip.status === 'cancelled'
                                                "
                                                class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700"
                                            >
                                                Annulé
                                            </span>

                                            <span
                                                v-else
                                                class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700"
                                            >
                                                Terminé
                                            </span>
                                        </td>
                                        <!-- Actions -->
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-right"
                                        >
                                            <div class="flex justify-end gap-2">
                                                <!-- Modifier -->
                                                <button
                                                    type="button"
                                                    @click="editTrip(trip)"
                                                    class="inline-flex items-center justify-center rounded-lg p-2 text-amber-600 hover:bg-amber-50"
                                                    title="Modifier"
                                                >
                                                    <Pencil class="h-4 w-4" />
                                                </button>

                                                <!-- Supprimer -->
                                                <button
                                                    type="button"
                                                    @click="deleteTrip(trip)"
                                                    class="inline-flex items-center justify-center rounded-lg bg-red-50 p-2 text-red-600 transition duration-200 hover:bg-red-100 hover:text-red-700 hover:scale-105"
                                                    title="Supprimer"
                                                >
                                                    <Trash2 class="h-4 w-4" />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Aucun trajet -->
                                    <tr v-if="trips.length === 0">
                                        <td
                                            colspan="7"
                                            class="px-6 py-10 text-center text-sm text-gray-500"
                                        >
                                            Aucun trajet enregistré pour le
                                            moment.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
