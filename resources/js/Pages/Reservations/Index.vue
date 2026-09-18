<script setup>
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import {
    CalendarCheck,
    Plus,
    Users,
    Bus,
    Pencil,
    Trash2,
    MapPin,
    X,
    Save,
    CheckCircle,
} from "lucide-vue-next";

const props = defineProps({
    reservations: {
        type: Array,
        default: () => [],
    },
    users: {
        type: Array,
        default: () => [],
    },
    trips: {
        type: Array,
        default: () => [],
    },
});

const showForm = ref(false);
const showSuccessPopup = ref(false);
const successMessage = ref("");
const editingReservation = ref(null);
const showDeleteModal = ref(false);
const reservationToDelete = ref(null);

const showSuccess = (message) => {
    successMessage.value = message;
    showSuccessPopup.value = true;

    setTimeout(() => {
        showSuccessPopup.value = false;
    }, 3000);
};
const editReservation = (reservation) => {
    editingReservation.value = reservation;

    form.user_id = reservation.user_id;
    form.trip_id = reservation.trip_id;
    form.seats = reservation.seats;

    showForm.value = true;
};
const form = useForm({
    user_id: "",
    trip_id: "",
    seats: 1,
});
const selectedTrip = () => {
    return props.trips.find((trip) => trip.id === Number(form.trip_id));
};

const totalAmount = () => {
    const trip = selectedTrip();

    if (!trip || !form.seats) {
        return 0;
    }

    return Number(trip.price) * Number(form.seats);
};
const deleteReservation = (reservation) => {
    reservationToDelete.value = reservation;
    showDeleteModal.value = true;
};

const confirmDeleteReservation = () => {
    if (!reservationToDelete.value) {
        return;
    }

    form.delete(route("reservations.destroy", reservationToDelete.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            reservationToDelete.value = null;

            showSuccess("Réservation supprimée avec succès !");
        },
    });
};
</script>

<template>
    <Head title="Réservations" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800"
                    >
                        Réservations
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Gérez les réservations des clients.
                    </p>
                </div>

                <button
                    type="button"
                    @click="showForm = true"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition duration-200 hover:bg-green-700 hover:shadow-md"
                >
                    <Plus :size="18" />
                    Nouvelle réservation
                </button>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Statistiques -->
                <div class="mb-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Total réservations -->
                    <div
                        class="group rounded-2xl border border-blue-100 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl"
                    >
                        <div class="flex items-center gap-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-600 transition duration-300 group-hover:scale-110 group-hover:rotate-3"
                            >
                                <CalendarCheck :size="24" />
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Total réservations
                                </p>

                                <p class="text-2xl font-bold text-gray-900">
                                    {{ reservations.length }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Clients -->
                    <div
                        class="group rounded-2xl border border-purple-100 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl"
                    >
                        <div class="flex items-center gap-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-purple-600 transition duration-300 group-hover:scale-110 group-hover:rotate-3"
                            >
                                <Users :size="24" />
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Clients disponibles
                                </p>

                                <p class="text-2xl font-bold text-gray-900">
                                    {{ users.length }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Trajets disponibles -->
                    <div
                        class="group rounded-2xl border border-orange-100 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl"
                    >
                        <div class="flex items-center gap-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-100 text-orange-600 transition duration-300 group-hover:scale-110 group-hover:rotate-3"
                            >
                                <Bus :size="24" />
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Trajets disponibles
                                </p>

                                <p class="text-2xl font-bold text-gray-900">
                                    {{ trips.length }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste des réservations -->
                <div
                    class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm"
                >
                    <div
                        class="flex items-center justify-between border-b border-gray-100 px-6 py-5"
                    >
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Liste des réservations
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Consultez les réservations enregistrées.
                            </p>
                        </div>
                    </div>

                    <div
                        v-if="reservations.length === 0"
                        class="px-6 py-16 text-center"
                    >
                        <CalendarCheck
                            class="mx-auto h-12 w-12 text-gray-300"
                        />

                        <h3 class="mt-4 text-lg font-semibold text-gray-900">
                            Aucune réservation
                        </h3>

                        <p class="mt-2 text-sm text-gray-500">
                            Commencez par créer une nouvelle réservation.
                        </p>

                        <button
                            type="button"
                            @click="showForm = true"
                            class="mt-5 inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-700"
                        >
                            <Plus :size="18" />
                            Nouvelle réservation
                        </button>
                    </div>

                    <div v-else class="w-full overflow-x-auto">
                        <table
                            class="w-full table-fixed divide-y divide-gray-100"
                        >
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="w-[17%] px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                    >
                                        Réservation
                                    </th>

                                    <th
                                        class="w-[18%] px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                    >
                                        Client
                                    </th>

                                    <th
                                        class="w-[25%] px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                    >
                                        Trajet
                                    </th>

                                    <th
                                        class="w-[10%] px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                    >
                                        Places
                                    </th>

                                    <th
                                        class="w-[12%] px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                    >
                                        Montant
                                    </th>

                                    <th
                                        class="w-[10%] px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                    >
                                        Statut
                                    </th>

                                    <th
                                        class="w-[10%] px-4 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-500"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100 bg-white">
                                <tr
                                    v-for="reservation in reservations"
                                    :key="reservation.id"
                                    class="transition hover:bg-gray-50"
                                >
                                    <!-- Réservation -->
                                    <td
                                        class="whitespace-nowrap px-4 py-4 align-middle"
                                    >
                                        <span
                                            class="font-semibold text-gray-900"
                                        >
                                            {{ reservation.reservation_number }}
                                        </span>

                                        <p class="mt-1 text-xs text-gray-500">
                                            {{
                                                new Date(
                                                    reservation.created_at,
                                                ).toLocaleDateString("fr-FR")
                                            }}
                                        </p>
                                    </td>

                                    <!-- Client -->
                                    <td class="max-w-0 px-4 py-4 align-middle">
                                        <p
                                            class="truncate font-medium text-gray-900"
                                        >
                                            {{ reservation.user?.name }}
                                        </p>

                                        <p
                                            class="truncate text-sm text-gray-500"
                                        >
                                            {{ reservation.user?.email }}
                                        </p>
                                    </td>

                                    <!-- Trajet -->
                                    <td class="max-w-0 px-4 py-4 align-middle">
                                        <div
                                            class="flex min-w-0 items-center gap-1.5 text-sm"
                                        >
                                            <MapPin
                                                :size="15"
                                                class="shrink-0 text-blue-500"
                                            />

                                            <span
                                                class="truncate font-medium text-gray-900"
                                            >
                                                {{
                                                    reservation.trip
                                                        ?.departure_city
                                                }}
                                            </span>

                                            <span
                                                class="shrink-0 text-gray-400"
                                            >
                                                →
                                            </span>

                                            <span
                                                class="truncate font-medium text-gray-900"
                                            >
                                                {{
                                                    reservation.trip
                                                        ?.destination_city
                                                }}
                                            </span>
                                        </div>

                                        <p
                                            class="mt-1 truncate text-xs text-gray-500"
                                        >
                                            {{
                                                new Date(
                                                    reservation.trip
                                                        ?.departure_date,
                                                ).toLocaleDateString("fr-FR")
                                            }}
                                            à
                                            {{
                                                reservation.trip?.departure_time
                                            }}
                                        </p>
                                    </td>

                                    <!-- Places -->
                                    <td
                                        class="whitespace-nowrap px-4 py-4 align-middle"
                                    >
                                        <span
                                            class="font-semibold text-gray-900"
                                        >
                                            {{ reservation.seats }}
                                        </span>

                                        <span class="text-sm text-gray-500">
                                            place(s)
                                        </span>
                                    </td>

                                    <!-- Montant -->
                                    <td
                                        class="whitespace-nowrap px-4 py-4 align-middle"
                                    >
                                        <span
                                            class="font-semibold text-gray-900"
                                        >
                                            {{
                                                Number(
                                                    reservation.total_amount,
                                                ).toLocaleString("fr-FR")
                                            }}
                                            Ar
                                        </span>
                                    </td>

                                    <!-- Statut -->
                                    <td
                                        class="whitespace-nowrap px-4 py-4 align-middle"
                                    >
                                        <span
                                            v-if="
                                                reservation.status === 'pending'
                                            "
                                            class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700"
                                        >
                                            En attente
                                        </span>

                                        <span
                                            v-else-if="
                                                reservation.status ===
                                                'confirmed'
                                            "
                                            class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700"
                                        >
                                            Confirmée
                                        </span>

                                        <span
                                            v-else
                                            class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700"
                                        >
                                            Annulée
                                        </span>
                                    </td>

                                    <!-- Actions -->
                                    <td
                                        class="whitespace-nowrap px-4 py-4 text-right align-middle"
                                    >
                                        <div class="flex justify-center gap-2">
                                            <!-- Modifier -->
                                            <button
                                                type="button"
                                                @click="
                                                    editReservation(reservation)
                                                "
                                                class="inline-flex items-center justify-center rounded-lg bg-amber-50 p-2 text-amber-600 transition hover:bg-amber-100"
                                                title="Modifier"
                                            >
                                                <Pencil :size="16" />
                                            </button>

                                            <!-- Supprimer -->
                                            <button
                                                type="button"
                                                @click="
                                                    deleteReservation(
                                                        reservation,
                                                    )
                                                "
                                                class="inline-flex items-center justify-center rounded-lg bg-red-50 p-2 text-red-600 transition hover:bg-red-100"
                                                title="Supprimer"
                                            >
                                                <Trash2 :size="16" />
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
        <!-- Modal nouvelle réservation -->
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="showForm"
                class="fixed inset-0 z-[90] flex items-center justify-center bg-black/50 px-4"
                @click.self="showForm = false"
            >
                <div
                    class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl"
                >
                    <!-- En-tête -->
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{
                                    editingReservation
                                        ? "Modifier la réservation"
                                        : "Nouvelle réservation"
                                }}
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                {{
                                    editingReservation
                                        ? "Modifiez les informations de la réservation."
                                        : "Enregistrez une réservation pour un client."
                                }}
                            </p>
                        </div>

                        <button
                            type="button"
                            @click="
                                showForm = false;
                                editingReservation = null;
                                form.reset();
                                form.seats = 1;
                            "
                            class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                        >
                            ✕
                        </button>
                    </div>

                    <!-- Formulaire -->
                    <form
                        @submit.prevent="
                            editingReservation
                                ? form.put(
                                      route(
                                          'reservations.update',
                                          editingReservation.id,
                                      ),
                                      {
                                          onSuccess: () => {
                                              showForm = false;
                                              editingReservation = null;
                                              form.reset();
                                              form.seats = 1;
                                              showSuccess(
                                                  'Réservation modifiée avec succès !',
                                              );
                                          },
                                      },
                                  )
                                : form.post(route('reservations.store'), {
                                      onSuccess: () => {
                                          showForm = false;
                                          form.reset();
                                          form.seats = 1;
                                          showSuccess(
                                              'Réservation créée avec succès !',
                                          );
                                      },
                                  })
                        "
                        class="space-y-5"
                    >
                        <!-- Client -->
                        <div>
                            <label
                                for="user_id"
                                class="mb-1 block text-sm font-medium text-gray-700"
                            >
                                Client
                            </label>

                            <select
                                id="user_id"
                                v-model="form.user_id"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            >
                                <option value="">Sélectionner un client</option>

                                <option
                                    v-for="user in users"
                                    :key="user.id"
                                    :value="user.id"
                                >
                                    {{ user.name }} — {{ user.email }}
                                </option>
                            </select>
                            <p
                                v-if="form.errors.user_id"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.user_id }}
                            </p>
                        </div>

                        <!-- Trajet -->
                        <div>
                            <label
                                for="trip_id"
                                class="mb-1 block text-sm font-medium text-gray-700"
                            >
                                Trajet
                            </label>

                            <select
                                id="trip_id"
                                v-model="form.trip_id"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            >
                                <option value="">Sélectionner un trajet</option>

                                <option
                                    v-for="trip in trips"
                                    :key="trip.id"
                                    :value="trip.id"
                                >
                                    {{ trip.departure_city }}
                                    →
                                    {{ trip.destination_city }}
                                    —
                                    {{
                                        new Date(
                                            trip.departure_date,
                                        ).toLocaleDateString("fr-FR")
                                    }}
                                    —
                                    {{ trip.price }} Ar
                                </option>
                            </select>
                            <p
                                v-if="form.errors.trip_id"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.trip_id }}
                            </p>
                        </div>

                        <!-- Nombre de places -->
                        <div>
                            <label
                                for="seats"
                                class="mb-1 block text-sm font-medium text-gray-700"
                            >
                                Nombre de places
                            </label>

                            <input
                                id="seats"
                                v-model.number="form.seats"
                                type="number"
                                min="1"
                                max="selectedTrip()?.available_seats"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            />
                            <p
                                v-if="selectedTrip()"
                                class="mt-1 text-xs text-gray-500"
                            >
                                {{ selectedTrip().available_seats }}
                                place{{
                                    selectedTrip().available_seats > 1
                                        ? "s"
                                        : ""
                                }}
                                disponible{{
                                    selectedTrip().available_seats > 1
                                        ? "s"
                                        : ""
                                }}
                            </p>
                            <p
                                v-if="form.errors.seats"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.seats }}
                            </p>
                        </div>
                        <div
                            v-if="selectedTrip()"
                            class="rounded-xl border border-green-100 bg-green-50 p-4"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-500">
                                        Montant total
                                    </p>

                                    <p
                                        class="mt-1 text-xl font-bold text-gray-900"
                                    >
                                        {{
                                            Number(
                                                totalAmount(),
                                            ).toLocaleString("fr-FR")
                                        }}
                                        Ar
                                    </p>
                                </div>

                                <div class="text-right">
                                    <p class="text-xs text-gray-500">
                                        Prix par place
                                    </p>

                                    <p
                                        class="text-sm font-semibold text-gray-700"
                                    >
                                        {{
                                            Number(
                                                selectedTrip().price,
                                            ).toLocaleString("fr-FR")
                                        }}
                                        Ar
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="flex justify-end gap-3 pt-2">
                            <button
                                type="button"
                                @click="showForm = false"
                                class="inline-flex items-center justify-center rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-200"
                            >
                                Annuler
                            </button>

                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-green-700 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                             <Save :size="17" />
                                {{
                                    form.processing
                                        ? "Enregistrement..."
                                        : "Enregistrer"
                                }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>
        <!-- Modal confirmation suppression -->
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="showDeleteModal"
                class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 px-4"
                @click.self="
                    showDeleteModal = false;
                    reservationToDelete = null;
                "
            >
                <div
                    class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl"
                >
                    <!-- En-tête -->
                    <div class="flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600"
                        >
                            <Trash2 :size="22" />
                        </div>

                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Supprimer la réservation ?
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Êtes-vous sûr de vouloir supprimer cette
                                réservation ? Cette action est irréversible.
                            </p>
                        </div>

                        <button
                            type="button"
                            @click="
                                showDeleteModal = false;
                                reservationToDelete = null;
                            "
                            class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                        >
                            <X :size="18" />
                        </button>
                    </div>

                    <!-- Réservation concernée -->
                    <div
                        v-if="reservationToDelete"
                        class="mt-5 rounded-xl border border-red-100 bg-red-50 p-4"
                    >
                        <p
                            class="text-xs font-medium uppercase tracking-wide text-red-500"
                        >
                            Réservation
                        </p>

                        <p class="mt-1 font-semibold text-gray-900">
                            {{ reservationToDelete.reservation_number }}
                        </p>

                        <p class="mt-1 text-sm text-gray-600">
                            {{ reservationToDelete.seats }} place(s)
                        </p>
                    </div>

                    <!-- Boutons -->
                    <div class="mt-6 flex justify-center gap-3">
                        <button
                            type="button"
                            @click="
                                showDeleteModal = false;
                                reservationToDelete = null;
                            "
                            class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                        >
                            Annuler
                        </button>

                        <button
                            type="button"
                            @click="confirmDeleteReservation"
                            :disabled="form.processing"
                            class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <Trash2 :size="17" />

                            {{
                                form.processing ? "Suppression..." : "Supprimer"
                            }}
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
        <!--    Popup de succès    -->
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
                class="fixed bottom-6 right-6 z-[999999] flex w-full max-w-sm items-start gap-3 rounded-xl border-2 border-green-200 bg-white p-4 opacity-100 shadow-2xl"
                style="isolation: isolate"
            >
                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-100"
                >
                    <CheckCircle :size="22" class="text-green-600" />
                </div>

                <div class="flex-1">
                    <p class="font-semibold text-gray-900">Opération réussie</p>

                    <p class="mt-1 text-sm text-gray-600">
                        {{ successMessage }}
                    </p>
                </div>

                <button
                    type="button"
                    @click="showSuccessPopup = false"
                    class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                >
                    <X :size="18" />
                </button>
            </div>
        </Transition>
    </AuthenticatedLayout>
</template>
