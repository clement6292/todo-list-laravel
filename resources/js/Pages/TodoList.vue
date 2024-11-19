<template>
  <div class=" max-w-lg mx-auto bg-white rounded-lg shadow-lg p-8 mt-10 " style="background-image: url('assets/images/bstrait-colore-vague-fond-transparent-elegant_1055-5316.jpg');">
    <h1 class="text-5xl text-center font-extrabold text-gray-800 mb-6">
      ToDo List
    </h1>

    <!-- lien d'export et formulaire d'exportation de fichier excel  -->
    <div>

      <!-- lien d'export de taches -->
     <div class="text-white px-4 py-2 rounded mb-8 text-center font-bold">
    <a :href="exportUrl" class="bg-green-500 text-white px-4 py-2 rounded mb-4 flex items-center justify-center">
       <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#FFBF66"><path d="M260-160q-91 0-155.5-63T40-377q0-78 47-139t123-78q25-92 100-149t170-57q117 0 198.5 81.5T760-520q69 8 114.5 59.5T920-340q0 75-52.5 127.5T740-160H520q-33 0-56.5-23.5T440-240v-206l-64 62-56-56 160-160 160 160-56 56-64-62v206h220q42 0 71-29t29-71q0-42-29-71t-71-29h-60v-80q0-83-58.5-141.5T480-720q-83 0-141.5 58.5T280-520h-20q-58 0-99 41t-41 99q0 58 41 99t99 41h100v80H260Zm220-280Z text-bold"/></svg>
        Exporter les Tâches
    </a>
</div>

      <!-- le formulaire importation de taches -->
      <div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-md bg-green-500 text-white px-4 py-2 rounded mb-8">
      <h2 class="text-lg font-semibold mb-4">Importer des Tâches</h2>
      <form @submit.prevent="importTasks" enctype="multipart/form-data">
      <input type="file" ref="fileInput" accept=".xlsx, .xls" required
             class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 p-2">
      <button type="submit" class="w-full bg-blue-500 text-white font-semibold font-bold py-2 rounded-md hover:bg-blue-600 transition duration-200 mt-2">
        Importer les Tâches
      </button>
    </form>
      </div>
    </div>



     <!-- Affichage du message de succès -->
    <div v-if="success" class="mb-4 p-4 bg-green-100 text-green-800 rounded">
      {{ success }}
    </div>

    <form @submit.prevent="addTask" class="flex mb-6">
      <input
        v-model="newTask"
        placeholder="Nouvelle tâche"
        class="flex-grow p-4 border border-gray-300 rounded-l-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150"
      />
      <button
        type="submit"
        class="bg-blue-600 text-white px-6 py-4 rounded-r-lg hover:bg-blue-700 transition duration-200"
      >
        Ajouter
      </button>
    </form>

    <ul class="divide-y divide-gray-200  bg-gray-100">
      <li
        v-for="task in tasks"
        :key="task.id"
        class="flex items-center justify-between py-4 hover:bg-gray-100 transition duration-150"
      >
        <div class="flex items-center space-x-4 ">
          <input
            type="checkbox"
            v-model="task.completed"
            @change="updateTask(task)"
            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
          />
          <span
            :class="{
              'line-through text-gray-400': task.completed,
              'text-gray-800': !task.completed,
            }"
            class="text-lg font-bold font-sans "
          >
            {{ task.title }}
          </span>
        </div>
        <div class="flex space-x-3">
          <button
            @click="editTask(task)"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200"
          >
            Modifier
          </button>
          <button
            @click="confirmDelete(task.id)"
            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition duration-200"
          >
            Supprimer
          </button>
        </div>
      </li>
    </ul>

    <!-- Modale de Confirmation -->
   <div
  v-if="showModal"
  class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
>
  <div class="bg-white rounded-lg p-6 shadow-lg max-w-sm w-full">
    <h2 class="text-xl font-semibold mb-4">Confirmation</h2>
    <p>Êtes-vous sûr de vouloir supprimer cette tâche ?</p>
    <div class="mt-4 flex justify-end">
      <button
        @click="deleteTask(selectedTaskId)"
        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition duration-200"
      >
        Supprimer
      </button>
      <button
        @click="cancelDelete"
        class="text-gray-600 hover:text-gray-700 px-4 py-2 rounded transition duration-200 ml-2"
      >
        Annuler
      </button>
    </div>
  </div>
</div>

    <div
      v-if="editingTask"
      class="mt-6 p-4 border border-gray-200 rounded-lg shadow-sm bg-gray-50"
    >
      <h2 class="text-xl font-semibold mb-2">Modifier la tâche</h2>
      <input
        v-model="editingTask.title"
        class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
      <div class="mt-4 flex space-x-2">
        <button
          @click="updateEditingTask"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-200"
        >
          Enregistrer
        </button>
        <button
          @click="cancelEditing"
          class="text-gray-600 hover:text-gray-700 transition duration-200"
        >
          Annuler
        </button>
      </div>
    </div>

    <!-- Notifications -->
    <div v-if="notifications.length" class="mb-6">
      <h2 class="text-lg font-bold text-white mb-4 p-5 text-center text-3xl bg-green-500">
        Notifications
      </h2>
      <ul class="space-y-2">
        <li
          v-for="notification in notifications"
          :key="notification.id"
          class="p-3 bg-gray-100 rounded-lg"
        >
          <p class="text-gray-800 font-bold text 2xl ">
            la tache "{{ JSON.parse(notification.data).task_title }}" a été
            {{ JSON.parse(notification.data).action }}
          </p>
          <p class="text-sm text-gray-500 font-semibold text 2xl">
            {{ new Date(notification.created_at).toLocaleString() }}
          </p>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, defineProps } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Swal from "sweetalert2";

// Props pour recevoir les tâches depuis le backend
const props = defineProps({
  tasks: Array,
  notifications: Array,
  success:String,
  importUrl: String,
  exportUrl: String,
});

// Références réactives
const newTask = ref("");
const editingTask = ref(null); // Pour gérer la tâche en cours d'édition
const showModal = ref(false); // Pour afficher la modale de confirmation
const selectedTaskId = ref(null); // Pour stocker l'ID de la tâche à supprimer
const notifications = ref(props.notifications || []); // Notifications initiales
const fileInput = ref(null);



// Méthode d'importation des tâches
const importTasks = async () => {
  const formData = new FormData();
  formData.append('file', fileInput.value.files[0]);

  try {
    await Inertia.post(props.importUrl, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
  } catch (error) {
    console.error("Erreur lors de l'importation des tâches:", error);
  }
};

// Fonction pour ajouter une tâche
const addTask = () => {
  if (!newTask.value.trim()) return; // Ne pas ajouter de tâches vides
  Inertia.post("/tasks", { title: newTask.value }).then(() => {
    Swal.fire({
      title: "Succès",
      text: "La tâche a été ajoutée avec succès!",
      icon: "success",
      showConfirmButton: false,
      willClose: () => {
        createNotification(`La tâche "${newTask.value}"`, "ajoutée.");
        showModal.value = false;
        newTask.value = ""; // Réinitialiser le champ d'entrée ici
      },
    });
  })
};

// Fonction pour créer une notification
const createNotification = (message, action) => {
  const notification = {
    id: Date.now(), // Utiliser un timestamp comme ID unique
    task_title: message,
    action,
    created_at: new Date().toISOString(),
  };
  notifications.value.push(notification);
};


// Fonction pour modifier une tâche
const editTask = (task) => {
  editingTask.value = { ...task }; // Créer une copie de la tâche à modifier
};

// Fonction pour annuler l'édition
const cancelEditing = () => {
  editingTask.value = null; // Réinitialiser l'édition
};

const updateEditingTask = async () => {
  try {

    console.log("ID de la tâche à mettre à jour:", editingTask.value.id); // Vérifiez ici
    if (editingTask.value) {
      await Inertia.put(`/tasks/${editingTask.value.id}`, {
        title: editingTask.value.title,
        completed: editingTask.value.completed,
      });
     Swal.fire({
    title: 'Succès',
    text: 'La tâche a été modifiée avec succès!',
    icon: 'success',
    timer: 3000, // Délai avant la fermeture automatique (en millisecondes)
    showConfirmButton: false, // Ne pas afficher le bouton "OK"
    willClose: () => {
        // Actions à exécuter après la fermeture
        createNotification(`La tâche "${editingTask.value.title}"`, 'modifiée.');
        editingTask.value = null; // Réinitialiser l'édition
    }
});
    }
  } catch (error) {
    console.error("Erreur lors de la mise à jour de la tâche:", error);
  }
};

// Fonction pour confirmer la suppression
const confirmDelete = (id) => {
  showModal.value = true;
  selectedTaskId.value = id;
};

// Fonction pour annuler la suppression
const cancelDelete = () => {
  showModal.value = false;
  selectedTaskId.value = null;
};

// Fonction pour supprimer une tâche
const deleteTask = async (id) => {
  try {
    await Inertia.delete(`/tasks/${id}`);
    Swal.fire({
      title: "Succès",
      text: "La tâche a été supprimée!",
      icon: "success",
      timer:3000,
      showConfirmButton: false, 
      willClose: () => {
        createNotification("La tâche a été", "supprimée.");
        showModal.value = true; // Ferme la modale après suppression
        selectedTaskId.value = null; // Réinitialiser l'ID sélectionné
      },
    });
  } catch (error) {
    console.error("Erreur lors de la suppression de la tâche:", error);
  }
};
</script>

<style scoped>
.line-through {
  text-decoration: line-through;
}

.font-sans {
  font-family: "Arial", sans-serif; /* Choisissez une police propre, par exemple Arial */
}
</style>