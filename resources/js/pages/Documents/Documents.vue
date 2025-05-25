<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { FileText, Upload, Search, Loader2, File, X, Download, Plus, Eye, EyeOff, Lock, Bot } from 'lucide-vue-next';
import { ref, computed } from 'vue';

const breadcrumbs = [
    { title: 'Documents', href: '/documents' },
];

const uploading = ref(false);
const uploadProgress = ref<{[key: string]: number}>({});
const searchQuery = ref('');
const categoryFilter = ref('');
const dateFilter = ref('');
const promptInput = ref('');
const fileInput = ref<HTMLInputElement | null>(null);
const showUploadModal = ref(false);
const showDeleteModal = ref(false);
const documentToDelete = ref<any>(null);
const deleteCountdown = ref(5);
const deleteTimer = ref<number | null>(null);
const canDelete = ref(false);
const showFileInfoModal = ref(false);
const showPermissionRequestModal = ref(false);
const selectedFile = ref<any>(null);
const permissionType = ref<'view' | 'download' | null>(null);

const categories = ['Policies', 'Training', 'Memorandum'];
const allDocuments = ref<any[]>([
  { 
    id: 1, 
    name: 'Employee Handbook.pdf', 
    category: 'Policies', 
    date: '2023-01-10', 
    size: '1.2MB', 
    type: 'pdf',
    uploader: 'John Doe',
    uploadDate: '2023-01-10',
    permissions: {
      view: ['admin', 'hr'],
      download: ['admin'],
      isPrivate: true
    },
    description: 'Company policies and guidelines for all employees'
  },
  { 
    id: 2, 
    name: 'Safety Training 2023.pptx', 
    category: 'Training', 
    date: '2023-03-15', 
    size: '3.4MB', 
    type: 'pptx',
    uploader: 'Jane Smith',
    uploadDate: '2023-03-15',
    permissions: {
      view: ['admin', 'hr', 'employee'],
      download: ['admin', 'hr'],
      isPrivate: false
    },
    description: 'Annual safety training presentation'
  },
  { 
    id: 3, 
    name: 'Holiday Memo.docx', 
    category: 'Memorandum', 
    date: '2023-12-01', 
    size: '0.8MB', 
    type: 'docx',
    uploader: 'HR Department',
    uploadDate: '2023-12-01',
    permissions: {
      view: ['admin', 'hr', 'employee'],
      download: ['admin', 'hr', 'employee'],
      isPrivate: false
    },
    description: 'Holiday schedule and office closure dates'
  },
  {
    id: 4,
    name: 'Code of Conduct.pdf',
    category: 'Policies',
    date: '2023-02-20',
    size: '0.9MB',
    type: 'pdf',
    uploader: 'Legal Department',
    uploadDate: '2023-02-20',
    permissions: {
      view: ['admin', 'hr', 'employee'],
      download: ['admin', 'hr'],
      isPrivate: false
    },
    description: 'Company code of conduct and ethical guidelines'
  },
  {
    id: 5,
    name: 'New Employee Onboarding.pptx',
    category: 'Training',
    date: '2023-04-05',
    size: '2.8MB',
    type: 'pptx',
    uploader: 'Training Department',
    uploadDate: '2023-04-05',
    permissions: {
      view: ['admin', 'hr'],
      download: ['admin'],
      isPrivate: true
    },
    description: 'New employee orientation and onboarding materials'
  },
  {
    id: 6,
    name: 'Q4 Financial Report.pdf',
    category: 'Memorandum',
    date: '2023-12-15',
    size: '1.5MB',
    type: 'pdf',
    uploader: 'Finance Department',
    uploadDate: '2023-12-15',
    permissions: {
      view: ['admin'],
      download: ['admin'],
      isPrivate: true
    },
    description: 'Quarterly financial performance report'
  },
  {
    id: 7,
    name: 'Remote Work Policy.pdf',
    category: 'Policies',
    date: '2023-06-01',
    size: '0.7MB',
    type: 'pdf',
    uploader: 'HR Department',
    uploadDate: '2023-06-01',
    permissions: {
      view: ['admin', 'hr', 'employee'],
      download: ['admin', 'hr'],
      isPrivate: false
    },
    description: 'Remote work guidelines and policies'
  },
  {
    id: 8,
    name: 'Leadership Training.pptx',
    category: 'Training',
    date: '2023-08-20',
    size: '4.2MB',
    type: 'pptx',
    uploader: 'Training Department',
    uploadDate: '2023-08-20',
    permissions: {
      view: ['admin', 'hr', 'manager'],
      download: ['admin', 'hr'],
      isPrivate: true
    },
    description: 'Leadership development and management training materials'
  },
  {
    id: 9,
    name: 'Office Renovation Notice.docx',
    category: 'Memorandum',
    date: '2023-09-10',
    size: '0.5MB',
    type: 'docx',
    uploader: 'Facilities Department',
    uploadDate: '2023-09-10',
    permissions: {
      view: ['admin', 'hr', 'employee'],
      download: ['admin', 'hr', 'employee'],
      isPrivate: false
    },
    description: 'Notice about upcoming office renovation schedule'
  },
  {
    id: 10,
    name: 'Data Security Policy.pdf',
    category: 'Policies',
    date: '2023-07-15',
    size: '1.1MB',
    type: 'pdf',
    uploader: 'IT Department',
    uploadDate: '2023-07-15',
    permissions: {
      view: ['admin', 'hr', 'employee'],
      download: ['admin', 'hr'],
      isPrivate: false
    },
    description: 'Data security and privacy guidelines'
  },
  {
    id: 11,
    name: 'Customer Service Training.pptx',
    category: 'Training',
    date: '2023-05-20',
    size: '3.1MB',
    type: 'pptx',
    uploader: 'Training Department',
    uploadDate: '2023-05-20',
    permissions: {
      view: ['admin', 'hr', 'employee'],
      download: ['admin', 'hr'],
      isPrivate: false
    },
    description: 'Customer service excellence training materials'
  },
  {
    id: 12,
    name: 'Annual Performance Review.docx',
    category: 'Memorandum',
    date: '2023-11-01',
    size: '0.6MB',
    type: 'docx',
    uploader: 'HR Department',
    uploadDate: '2023-11-01',
    permissions: {
      view: ['admin', 'hr', 'manager'],
      download: ['admin', 'hr'],
      isPrivate: true
    },
    description: 'Annual performance review guidelines and templates'
  }
]);

const filteredDocuments = computed(() => {
  return allDocuments.value.filter(doc => {
    const matchesSearch =
      !searchQuery.value ||
      doc.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      (promptInput.value && doc.name.toLowerCase().includes(promptInput.value.toLowerCase()));
    const matchesCategory = !categoryFilter.value || doc.category === categoryFilter.value;
    const matchesDate = !dateFilter.value || doc.date.startsWith(dateFilter.value);
    return matchesSearch && matchesCategory && matchesDate;
  });
});

const totalDocs = computed(() => allDocuments.value.length);
const docsByCategory = computed(() => {
  const result: Record<string, number> = {};
  categories.forEach(cat => {
    result[cat] = allDocuments.value.filter(doc => doc.category === cat).length;
  });
  return result;
});

const currentPage = ref(1);
const itemsPerPage = ref(5);
const pageSizeOptions = [5, 10, 20, 100];
const years = ['2024', '2023', '2022', '2021', '2020'];

const paginatedDocuments = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredDocuments.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(filteredDocuments.value.length / itemsPerPage.value);
});

function goToPage(page: number) {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
}

function handlePageSizeChange() {
  // Reset to first page when changing page size
  currentPage.value = 1;
}

function handleFileChange(e: Event) {
  const input = e.target as HTMLInputElement;
  if (!input.files) return;
  for (const file of Array.from(input.files)) {
    if (!validateFile(file)) continue;
    uploadFile(file);
  }
}
function handleDrop(e: DragEvent) {
  e.preventDefault();
  if (!e.dataTransfer) return;
  for (const file of Array.from(e.dataTransfer.files)) {
    if (!validateFile(file)) continue;
    uploadFile(file);
  }
}
function validateFile(file: File) {
  const allowed = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];
  const maxSize = 10 * 1024 * 1024;
  if (!allowed.includes(file.type)) {
    alert('Invalid file type. Only PDF, DOCX, PPTX allowed.');
    return false;
  }
  if (file.size > maxSize) {
    alert('File too large. Max 10MB.');
    return false;
  }
  return true;
}
function uploadFile(file: File) {
  uploading.value = true;
  uploadProgress.value[file.name] = 0;
  // Simulate upload
  const interval = setInterval(() => {
    if (uploadProgress.value[file.name] >= 100) {
      clearInterval(interval);
      uploading.value = false;
      allDocuments.value.unshift({
        id: Date.now(),
        name: file.name,
        category: categoryFilter.value || 'Uncategorized',
        date: new Date().toISOString().slice(0, 10),
        size: (file.size / (1024 * 1024)).toFixed(1) + 'MB',
        type: file.name.split('.').pop(),
      });
      delete uploadProgress.value[file.name];
    } else {
      uploadProgress.value[file.name] += 10 + Math.random() * 10;
      if (uploadProgress.value[file.name] > 100) uploadProgress.value[file.name] = 100;
    }
  }, 200);
}
function removeFile(id: number) {
  allDocuments.value = allDocuments.value.filter(doc => doc.id !== id);
}
function downloadFile(doc: any) {
  alert('Download: ' + doc.name);
}
function viewFile(doc: any) {
  alert('View: ' + doc.name);
}
function openFileDialog() {
  if (fileInput.value && typeof fileInput.value !== 'string') {
    fileInput.value.click();
  }
}
function openUploadModal() {
  showUploadModal.value = true;
}
function closeUploadModal() {
  showUploadModal.value = false;
}
function confirmDelete(doc: any) {
  documentToDelete.value = doc;
  showDeleteModal.value = true;
  deleteCountdown.value = 5;
  canDelete.value = false;
  startDeleteCountdown();
}
function startDeleteCountdown() {
  if (deleteTimer.value) {
    clearInterval(deleteTimer.value);
  }
  deleteTimer.value = window.setInterval(() => {
    if (deleteCountdown.value > 0) {
      deleteCountdown.value--;
    } else {
      if (deleteTimer.value) {
        clearInterval(deleteTimer.value);
      }
      canDelete.value = true;
    }
  }, 1000);
}
function cancelDelete() {
  if (deleteTimer.value) {
    clearInterval(deleteTimer.value);
  }
  showDeleteModal.value = false;
  documentToDelete.value = null;
  deleteCountdown.value = 5;
  canDelete.value = false;
}
function handleDelete() {
  if (canDelete.value && documentToDelete.value) {
    removeFile(documentToDelete.value.id);
    cancelDelete();
  }
}
function showFileDetails(doc: any) {
  selectedFile.value = doc;
  showFileInfoModal.value = true;
}
function requestPermission(type: 'view' | 'download') {
  permissionType.value = type;
  showPermissionRequestModal.value = true;
}
function handleViewFile(doc: any) {
  if (doc.permissions.isPrivate) {
    requestPermission('view');
  } else {
    viewFile(doc);
  }
}
function handleDownloadFile(doc: any) {
  if (doc.permissions.isPrivate) {
    requestPermission('download');
  } else {
    downloadFile(doc);
  }
}
function submitPermissionRequest() {
  // Here you would typically make an API call to request permission
  alert(`Permission request for ${permissionType.value} access to ${selectedFile.value.name} has been submitted.`);
  showPermissionRequestModal.value = false;
  permissionType.value = null;
}
</script>

<template>
  <Head title="Documents" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div 
      class="p-4 md:p-8 min-h-screen"
      @drop.prevent="handleDrop"
      @dragover.prevent
    >
      <div class="w-full">
        <div class="bg-white/60 backdrop-blur-md border border-white/30 shadow-lg rounded-2xl p-8 flex flex-col gap-6">
          <!-- Header & Quick Stats -->
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-2">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-blue-100 rounded-lg">
                <FileText class="w-6 h-6 text-blue-600" />
              </div>
              <div>
                <h2 class="text-2xl font-bold text-gray-900">Documents</h2>
                <p class="text-gray-500 text-sm mt-1">All your company documents in one place. Upload, search, and manage with ease.</p>
              </div>
            </div>
            <div class="flex gap-4 items-center">
              <div class="flex flex-col items-center">
                <span class="text-xs text-gray-500">Total</span>
                <span class="text-lg font-bold text-blue-700">{{ totalDocs }}</span>
              </div>
              <div v-for="cat in categories" :key="cat" class="flex flex-col items-center">
                <span class="text-xs text-gray-500">{{ cat }}</span>
                <span class="text-lg font-bold text-blue-700">{{ docsByCategory[cat] }}</span>
              </div>
              <button @click="openUploadModal" class="ml-2 p-2 rounded-full bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-blue-300 transition flex items-center justify-center group" title="New Document">
                <Plus class="w-5 h-5 text-white group-hover:scale-110 transition-transform" />
                <span class="sr-only">New Document</span>
              </button>
            </div>
          </div>
          <!-- Search & Filters -->
          <div class="flex flex-col md:flex-row md:items-center gap-3 mb-2 bg-blue-50/40 rounded-xl p-4 border border-blue-100">
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
              <div class="relative flex-1 min-w-[180px]">
                <input v-model="searchQuery" type="text" placeholder="Search documents by name or keyword..." class="w-full rounded-lg border border-gray-200 py-2.5 pl-10 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200 h-[44px]" />
                <Search class="absolute left-3 top-3 w-4 h-4 text-gray-400" />
              </div>
              <select v-model="categoryFilter" class="min-w-[180px] px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-200 h-[44px]">
                <option value="">All Categories</option>
                <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
              </select>
              <select v-model="dateFilter" class="min-w-[180px] px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-200 h-[44px]">
                <option value="">All Years</option>
                <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
              </select>
            </div>
            <div class="relative flex-1 min-w-[180px] mt-3 md:mt-0">
              <input v-model="promptInput" type="text" placeholder="Ask something like 'Show me all training documents from 2023'" class="w-full rounded-lg border border-gray-200 py-2.5 pl-10 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200 h-[44px]" />
              <Bot class="absolute left-3 top-3 w-4 h-4 text-purple-500" />
            </div>
          </div>
          <!-- Document List -->
          <div class="overflow-x-auto mt-4 flex-1">
            <table class="min-w-full text-sm rounded-xl overflow-hidden">
              <thead>
                <tr class="bg-blue-50 text-blue-700">
                  <th class="p-2 text-left">Name</th>
                  <th class="p-2 text-left">Category</th>
                  <th class="p-2 text-left">Date</th>
                  <th class="p-2 text-left">Size</th>
                  <th class="p-2 text-left">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="doc in paginatedDocuments" :key="doc.id" class="border-b hover:bg-blue-50/40 transition">
                  <td class="p-2">
                    <div class="flex items-center gap-2 min-w-0">
                      <File class="w-5 h-5 text-blue-400 flex-shrink-0" />
                      <span class="font-medium text-gray-900 truncate">{{ doc.name }}</span>
                    </div>
                  </td>
                  <td class="p-2">
                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold" :class="{
                      'bg-blue-100 text-blue-700': doc.category === 'Policies',
                      'bg-green-100 text-green-700': doc.category === 'Training',
                      'bg-yellow-100 text-yellow-700': doc.category === 'Memorandum',
                      'bg-gray-100 text-gray-700': !categories.includes(doc.category)
                    }">{{ doc.category }}</span>
                  </td>
                  <td class="p-2">{{ new Date(doc.date).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' }) }}</td>
                  <td class="p-2">{{ doc.size }}</td>
                  <td class="p-2 flex gap-2">
                    <button 
                      @click="showFileDetails(doc)" 
                      class="p-1.5 rounded bg-gray-100 hover:bg-gray-200 text-gray-700" 
                      title="View Details"
                    >
                      <FileText class="w-4 h-4" />
                    </button>
                    <button 
                      @click="handleViewFile(doc)" 
                      class="p-1.5 rounded bg-gray-100 hover:bg-gray-200 text-gray-700" 
                      :title="doc.permissions.isPrivate ? 'Request View Permission' : 'View'"
                    >
                      <EyeOff v-if="doc.permissions.isPrivate" class="w-4 h-4" />
                      <Eye v-else class="w-4 h-4" />
                    </button>
                    <button 
                      @click="handleDownloadFile(doc)" 
                      class="p-1.5 rounded bg-blue-100 hover:bg-blue-200 text-blue-700" 
                      :title="doc.permissions.isPrivate ? 'Request Download Permission' : 'Download'"
                    >
                      <Download class="w-4 h-4" />
                    </button>
                    <button @click="confirmDelete(doc)" class="p-1.5 rounded bg-red-100 hover:bg-red-200 text-red-700" title="Delete">
                      <X class="w-4 h-4" />
                    </button>
                  </td>
                </tr>
                <tr v-if="filteredDocuments.length === 0">
                  <td colspan="5" class="text-center text-gray-400 py-8">No documents found.</td>
                </tr>
              </tbody>
            </table>

            <!-- Pagination -->
            <div v-if="totalPages > 1" class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mt-6 w-full px-2 border-t pt-4">
              <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Show</span>
                <select 
                  v-model="itemsPerPage" 
                  @change="handlePageSizeChange"
                  class="rounded-lg border border-gray-200 py-1 px-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200 h-[36px]"
                >
                  <option v-for="size in pageSizeOptions" :key="size" :value="size">{{ size }}</option>
                </select>
                <span class="text-sm text-gray-500">entries</span>
              </div>
              <div class="text-xs text-gray-400 sm:text-sm sm:text-gray-500 text-left">
                Showing {{ (currentPage - 1) * itemsPerPage + 1 }} to {{ Math.min(currentPage * itemsPerPage, filteredDocuments.length) }} of {{ filteredDocuments.length }} documents
              </div>
              <div class="flex items-center gap-2 justify-start">
                <button 
                  @click="goToPage(currentPage - 1)"
                  :disabled="currentPage === 1"
                  class="h-9 px-4 rounded-lg border text-sm font-medium transition-colors duration-150 flex items-center justify-center select-none"
                  :class="currentPage === 1 ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' : 'bg-white text-gray-700 border-gray-300 hover:bg-blue-50 hover:border-blue-300'"
                >
                  Previous
                </button>
                <div class="flex gap-1">
                  <button 
                    v-for="page in totalPages" 
                    :key="page"
                    @click="goToPage(page)"
                    class="w-9 h-9 rounded-lg text-sm font-medium transition-colors duration-150 flex items-center justify-center select-none border"
                    :class="page === currentPage ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-blue-50 hover:border-blue-300'"
                  >
                    {{ page }}
                  </button>
                </div>
                <button 
                  @click="goToPage(currentPage + 1)"
                  :disabled="currentPage === totalPages"
                  class="h-9 px-4 rounded-lg border text-sm font-medium transition-colors duration-150 flex items-center justify-center select-none"
                  :class="currentPage === totalPages ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' : 'bg-white text-gray-700 border-gray-300 hover:bg-blue-50 hover:border-blue-300'"
                >
                  Next
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>

  <!-- Delete Confirmation Modal -->
  <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30">
    <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md relative">
      <h3 class="text-lg font-bold mb-2 flex items-center gap-2 text-red-600">
        <X class="w-5 h-5" /> Confirm Delete
      </h3>
      <p class="text-gray-600 mb-6">
        Are you sure you want to delete "{{ documentToDelete?.name }}"? This action cannot be undone.
      </p>
      <div class="flex justify-end gap-3">
        <button 
          @click="cancelDelete"
          class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
        >
          Cancel
        </button>
        <button 
          @click="handleDelete"
          :disabled="!canDelete"
          :class="[
            'px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors',
            canDelete 
              ? 'bg-red-600 hover:bg-red-700 cursor-pointer' 
              : 'bg-red-400 cursor-not-allowed'
          ]"
        >
          {{ canDelete ? 'Delete' : `Please wait ${deleteCountdown}s` }}
        </button>
      </div>
    </div>
  </div>

  <!-- File Information Modal -->
  <div v-if="showFileInfoModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30">
    <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md relative">
      <button @click="showFileInfoModal = false" class="absolute top-3 right-3 p-2 rounded-full bg-gray-100 hover:bg-gray-200">
        <X class="w-5 h-5 text-gray-500" />
      </button>
      <div v-if="selectedFile" class="space-y-4">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-blue-100 rounded-lg">
            <FileText class="w-6 h-6 text-blue-600" />
          </div>
          <div>
            <h3 class="text-lg font-bold text-gray-900">{{ selectedFile.name }}</h3>
            <p class="text-sm text-gray-500">{{ selectedFile.description }}</p>
          </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4 text-sm">
          <div>
            <span class="text-gray-500">Uploaded by</span>
            <p class="font-medium text-gray-900">{{ selectedFile.uploader }}</p>
          </div>
          <div>
            <span class="text-gray-500">Upload Date</span>
            <p class="font-medium text-gray-900">{{ new Date(selectedFile.uploadDate).toLocaleDateString() }}</p>
          </div>
          <div>
            <span class="text-gray-500">Category</span>
            <p class="font-medium text-gray-900">{{ selectedFile.category }}</p>
          </div>
          <div>
            <span class="text-gray-500">File Type</span>
            <p class="font-medium text-gray-900">{{ selectedFile.type.toUpperCase() }}</p>
          </div>
          <div>
            <span class="text-gray-500">Size</span>
            <p class="font-medium text-gray-900">{{ selectedFile.size }}</p>
          </div>
          <div>
            <span class="text-gray-500">Access Level</span>
            <p class="font-medium" :class="selectedFile.permissions.isPrivate ? 'text-red-600' : 'text-green-600'">
              {{ selectedFile.permissions.isPrivate ? 'Private' : 'Public' }}
            </p>
          </div>
        </div>

        <div class="border-t pt-4">
          <h4 class="text-sm font-medium text-gray-700 mb-2">Permissions</h4>
          <div class="space-y-2">
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-600">View Access</span>
              <div class="flex gap-1">
                <span v-for="role in selectedFile.permissions.view" :key="role"
                  class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700">
                  {{ role }}
                </span>
              </div>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-600">Download Access</span>
              <div class="flex gap-1">
                <span v-for="role in selectedFile.permissions.download" :key="role"
                  class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700">
                  {{ role }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Permission Request Modal -->
  <div v-if="showPermissionRequestModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30">
    <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md relative">
      <button @click="showPermissionRequestModal = false" class="absolute top-3 right-3 p-2 rounded-full bg-gray-100 hover:bg-gray-200">
        <X class="w-5 h-5 text-gray-500" />
      </button>
      <div class="space-y-4">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-yellow-100 rounded-lg">
            <Lock class="w-6 h-6 text-yellow-600" />
          </div>
          <div>
            <h3 class="text-lg font-bold text-gray-900">Request Permission</h3>
            <p class="text-sm text-gray-500">
              This file requires permission to {{ permissionType === 'view' ? 'view' : 'download' }}
            </p>
          </div>
        </div>
        
        <div class="bg-yellow-50 p-4 rounded-lg">
          <p class="text-sm text-yellow-800">
            You need permission to {{ permissionType === 'view' ? 'view' : 'download' }} this file. 
            Would you like to request access?
          </p>
        </div>

        <div class="flex justify-end gap-3">
          <button 
            @click="showPermissionRequestModal = false"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
          >
            Cancel
          </button>
          <button 
            @click="submitPermissionRequest"
            class="px-4 py-2 text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 rounded-lg transition-colors"
          >
            Request Access
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Upload Modal -->
  <div v-if="showUploadModal" class="fixed inset-0 z-[100] flex items-center justify-center">
    <div class="fixed inset-0 bg-black/30" @click="closeUploadModal"></div>
    <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md relative z-[101]">
      <button @click="closeUploadModal" class="absolute top-3 right-3 p-2 rounded-full bg-gray-100 hover:bg-gray-200">
        <X class="w-5 h-5 text-gray-500" />
      </button>
      <h3 class="text-lg font-bold mb-2 flex items-center gap-2"><Upload class="w-5 h-5 text-blue-500" /> Upload Document</h3>
      <div
        class="flex flex-col items-center justify-center border-2 border-dashed border-blue-200 rounded-xl bg-blue-50/40 p-8 cursor-pointer hover:bg-blue-100/60 transition"
        @mousedown.prevent="openFileDialog"
      >
        <Upload class="w-10 h-10 text-blue-400 mb-2" />
        <span class="text-gray-700 font-medium">Drag & drop files here or <span class="underline text-blue-600">browse</span></span>
        <span class="text-xs text-gray-500 mt-1">PDF, DOCX, PPTX. Max 10MB each.</span>
        <input ref="fileInput" type="file" class="hidden" multiple @change="handleFileChange" />
      </div>
      <div v-if="Object.keys(uploadProgress).length" class="mt-2 space-y-2">
        <div v-for="(progress, name) in uploadProgress" :key="name" class="flex items-center gap-2">
          <Loader2 class="w-4 h-4 animate-spin text-blue-500" />
          <span class="text-xs text-gray-700">Uploading {{ name }}</span>
          <div class="flex-1 bg-blue-100 rounded h-2 mx-2">
            <div class="bg-blue-500 h-2 rounded transition-all" :style="{ width: progress + '%' }"></div>
          </div>
          <span class="text-xs text-gray-500">{{ Math.round(progress) }}%</span>
        </div>
      </div>
    </div>
  </div>
</template> 