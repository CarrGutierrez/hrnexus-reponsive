<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import Card from '@/components/ui/card/Card.vue';
import Button from '@/components/ui/button/Button.vue';
import { ref, computed } from 'vue';
import { Mail, Phone, Pencil, Trash2, Eye, Search, Users, CheckCircle, XCircle } from 'lucide-vue-next';
import { Transition } from 'vue';

interface Employee {
  id: number;
  name: string;
  roles: string[];
  department: string;
  email: string;
  phone: string;
  status: 'Active' | 'Inactive';
}

const employees = ref<Employee[]>([
  {
    id: 1,
    name: 'John Smith',
    roles: ['Developer', 'Team Lead'],
    department: 'Engineering',
    email: 'john.smith@company.com',
    phone: '+639 092 140 862',
    status: 'Active'
  },
  {
    id: 2,
    name: 'Sarah Johnson',
    roles: ['Product Manager'],
    department: 'Product',
    email: 'sarah.j@company.com',
    phone: '+639 123 456 789',
    status: 'Active'
  },
  {
    id: 3,
    name: 'Michael Brown',
    roles: ['Developer'],
    department: 'Engineering',
    email: 'michael.b@company.com',
    phone: '+639 234 567 890',
    status: 'Active'
  },
  {
    id: 4,
    name: 'Emily Davis',
    roles: ['HR Manager'],
    department: 'Human Resources',
    email: 'emily.d@company.com',
    phone: '+639 345 678 901',
    status: 'Active'
  },
  {
    id: 5,
    name: 'David Wilson',
    roles: ['Developer', 'Team Lead'],
    department: 'Engineering',
    email: 'david.w@company.com',
    phone: '+639 456 789 012',
    status: 'Active'
  },
  {
    id: 6,
    name: 'Lisa Anderson',
    roles: ['UI/UX Designer'],
    department: 'Design',
    email: 'lisa.a@company.com',
    phone: '+639 567 890 123',
    status: 'Active'
  },
  {
    id: 7,
    name: 'Robert Taylor',
    roles: ['Developer'],
    department: 'Engineering',
    email: 'robert.t@company.com',
    phone: '+639 678 901 234',
    status: 'Inactive'
  },
  {
    id: 8,
    name: 'Jennifer Martinez',
    roles: ['Product Manager'],
    department: 'Product',
    email: 'jennifer.m@company.com',
    phone: '+639 789 012 345',
    status: 'Active'
  },
  {
    id: 9,
    name: 'James Wilson',
    roles: ['Developer'],
    department: 'Engineering',
    email: 'james.w@company.com',
    phone: '+639 890 123 456',
    status: 'Active'
  },
  {
    id: 10,
    name: 'Patricia Garcia',
    roles: ['HR Manager'],
    department: 'Human Resources',
    email: 'patricia.g@company.com',
    phone: '+639 901 234 567',
    status: 'Inactive'
  },
  {
    id: 11,
    name: 'Thomas Lee',
    roles: ['Developer', 'Team Lead'],
    department: 'Engineering',
    email: 'thomas.l@company.com',
    phone: '+639 012 345 678',
    status: 'Active'
  },
  {
    id: 12,
    name: 'Elizabeth White',
    roles: ['UI/UX Designer'],
    department: 'Design',
    email: 'elizabeth.w@company.com',
    phone: '+639 123 456 789',
    status: 'Active'
  },
  {
    id: 13,
    name: 'Daniel Clark',
    roles: ['Developer'],
    department: 'Engineering',
    email: 'daniel.c@company.com',
    phone: '+639 234 567 890',
    status: 'Inactive'
  },
  {
    id: 14,
    name: 'Margaret Rodriguez',
    roles: ['Product Manager'],
    department: 'Product',
    email: 'margaret.r@company.com',
    phone: '+639 345 678 901',
    status: 'Active'
  },
  {
    id: 15,
    name: 'Christopher Lewis',
    roles: ['Developer', 'Team Lead'],
    department: 'Engineering',
    email: 'christopher.l@company.com',
    phone: '+639 456 789 012',
    status: 'Active'
  },
  {
    id: 16,
    name: 'Susan Walker',
    roles: ['HR Manager'],
    department: 'Human Resources',
    email: 'susan.w@company.com',
    phone: '+639 567 890 123',
    status: 'Active'
  },
  {
    id: 17,
    name: 'Kevin Hall',
    roles: ['Developer'],
    department: 'Engineering',
    email: 'kevin.h@company.com',
    phone: '+639 678 901 234',
    status: 'Inactive'
  },
  {
    id: 18,
    name: 'Nancy Allen',
    roles: ['UI/UX Designer'],
    department: 'Design',
    email: 'nancy.a@company.com',
    phone: '+639 789 012 345',
    status: 'Active'
  },
  {
    id: 19,
    name: 'George Young',
    roles: ['Developer', 'Team Lead'],
    department: 'Engineering',
    email: 'george.y@company.com',
    phone: '+639 890 123 456',
    status: 'Active'
  },
  {
    id: 20,
    name: 'Karen King',
    roles: ['Product Manager'],
    department: 'Product',
    email: 'karen.k@company.com',
    phone: '+639 901 234 567',
    status: 'Inactive'
  }
]);

const search = ref('');
const currentPage = ref(1);
const pageSize = ref(10);
const pageSizeOptions = [10, 20, 50, 100];

// Filter states
const selectedDepartment = ref('');
const selectedRole = ref('');
const selectedStatus = ref('');

// Get unique values for filters
const departments = computed(() => Array.from(new Set(employees.value.map(e => e.department))));
const roles = computed(() => Array.from(new Set(employees.value.flatMap(e => e.roles))));
const statuses = ['Active', 'Inactive'];

const filteredEmployees = computed(() => {
  return employees.value.filter(employee => {
    // Search filter
    const searchMatch = 
      employee.name.toLowerCase().includes(search.value.toLowerCase()) ||
      employee.roles.some(role => role.toLowerCase().includes(search.value.toLowerCase())) ||
      employee.department.toLowerCase().includes(search.value.toLowerCase());

    // Department filter
    const departmentMatch = !selectedDepartment.value || 
      employee.department === selectedDepartment.value;

    // Role filter
    const roleMatch = !selectedRole.value || 
      employee.roles.includes(selectedRole.value);

    // Status filter
    const statusMatch = !selectedStatus.value || 
      employee.status === selectedStatus.value;

    return searchMatch && departmentMatch && roleMatch && statusMatch;
  });
});

const paginatedEmployees = computed(() => {
  const start = (currentPage.value - 1) * pageSize.value;
  const end = start + pageSize.value;
  return filteredEmployees.value.slice(start, end);
});

const totalPages = computed(() => Math.ceil(filteredEmployees.value.length / pageSize.value));

function changePage(page: number) {
  currentPage.value = page;
}

function changePageSize(size: number) {
  pageSize.value = size;
  currentPage.value = 1; // Reset to first page when changing page size
}

const selected = ref<number[]>([]);

function toggleSelectAll() {
  if (selected.value.length === filteredEmployees.value.length) {
    selected.value = [];
  } else {
    selected.value = filteredEmployees.value.map(e => e.id);
  }
}

function viewProfile(id: number) {
  // Navigate to EmployeeProfile page
  window.location.href = `/employees/${id}`;
}

function updateStatusSelected(status: 'Active' | 'Inactive') {
  employees.value = employees.value.map(e => selected.value.includes(e.id) ? { ...e, status } : e);
  selected.value = [];
}

// Add state for delete confirmation modal
const showDeleteModal = ref(false);
const employeeToDelete = ref<Employee | null>(null);

// Add delete confirmation function
function confirmDelete(employee: Employee) {
  employeeToDelete.value = employee;
  showDeleteModal.value = true;
}

// Add actual delete function
function deleteEmployee() {
  if (employeeToDelete.value) {
    employees.value = employees.value.filter(e => e.id !== employeeToDelete.value?.id);
    showDeleteModal.value = false;
    employeeToDelete.value = null;
  }
}
</script>

<template>
  <Head title="Employees" />
  <AppLayout :breadcrumbs="[{ title: 'Employees', href: '/employees' }]">
    <div class="p-4">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold flex items-center gap-2">
          <Users class="w-6 h-6" /> Employee Management
        </h1>
      </div>
      <Card class="p-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:gap-4 gap-2 mb-4">
          <h2 class="text-lg font-semibold flex-1">Employees</h2>
          <div class="flex flex-col sm:flex-row flex-wrap gap-2 w-full">
            <div class="relative w-full sm:w-auto min-w-[160px]">
              <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
              <input 
                v-model="search" 
                type="text" 
                placeholder="Search employees..." 
                class="w-full pl-10 pr-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary min-w-[160px]"
              />
            </div>
            <select 
              v-model="selectedDepartment" 
              class="w-full sm:w-auto min-w-[160px] px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
            >
              <option value="">All Departments</option>
              <option v-for="dept in departments" :key="dept" :value="dept">{{ dept }}</option>
            </select>
            <select 
              v-model="selectedRole" 
              class="w-full sm:w-auto min-w-[160px] px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
            >
              <option value="">All Roles</option>
              <option v-for="role in roles" :key="role" :value="role">{{ role }}</option>
            </select>
            <select 
              v-model="selectedStatus" 
              class="w-full sm:w-auto min-w-[160px] px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
            >
              <option value="">All Status</option>
              <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
            </select>
          </div>
        </div>

        <!-- Bulk Actions -->
        <Transition
            enter-active-class="transition-all duration-200 ease-out"
            enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition-all duration-150 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-2"
        >
            <div v-if="selected.length > 0" class="mb-4 p-2 bg-gray-50 rounded-lg flex flex-wrap items-center gap-2 w-full">
                <span class="text-sm text-gray-600">{{ selected.length }} selected</span>
                <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                    <Button 
                        size="sm" 
                        variant="outline"
                        @click="updateStatusSelected('Active')"
                        class="transition-colors duration-200 text-green-600 hover:text-green-700 hover:bg-green-50"
                    >
                        <CheckCircle class="w-4 h-4 mr-1" />
                        Set Active
                    </Button>
                    <Button 
                        size="sm" 
                        variant="outline"
                        @click="updateStatusSelected('Inactive')"
                        class="transition-colors duration-200 text-red-600 hover:text-red-700 hover:bg-red-50"
                    >
                        <XCircle class="w-4 h-4 mr-1" />
                        Set Inactive
                    </Button>
                    <Button 
                        size="sm" 
                        variant="outline"
                        @click="selected = []"
                        class="transition-colors duration-200"
                    >
                        Clear Selection
                    </Button>
                </div>
            </div>
        </Transition>

        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b">
                <th class="text-center py-2 px-4 w-12">
                  <div class="relative inline-flex items-center">
                    <input 
                      type="checkbox" 
                      :checked="selected.length === filteredEmployees.length"
                      @change="toggleSelectAll"
                      class="peer rounded transition-all duration-200 ease-in-out cursor-pointer
                             checked:bg-primary checked:border-primary
                             focus:ring-2 focus:ring-primary/20 focus:ring-offset-0
                             hover:border-primary/50
                             w-4 h-4 border-2 appearance-none"
                    />
                    <svg 
                      class="absolute w-4 h-4 text-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200 pointer-events-none"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="3"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                  </div>
                </th>
                <th class="text-left py-2 px-4 text-sm font-medium">Name</th>
                <th class="text-left py-2 px-4 text-sm font-medium">Roles</th>
                <th class="text-left py-2 px-4 text-sm font-medium">Department</th>
                <th class="text-left py-2 px-4 text-sm font-medium">Contact</th>
                <th class="text-left py-2 px-4 text-sm font-medium">Status</th>
                <th class="text-center py-2 px-4 text-sm font-medium">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr 
                v-for="employee in paginatedEmployees" 
                :key="employee.id" 
                class="border-b transition-colors duration-200 ease-in-out"
                :class="[
                  selected.includes(employee.id) 
                    ? 'bg-primary/5 hover:bg-primary/10' 
                    : 'hover:bg-gray-50'
                ]"
              >
                <td class="text-center py-2 px-4">
                  <div class="relative inline-flex items-center">
                    <input 
                      type="checkbox" 
                      :checked="selected.includes(employee.id)"
                      @change="selected.includes(employee.id) ? selected = selected.filter(id => id !== employee.id) : selected.push(employee.id)"
                      class="peer rounded transition-all duration-200 ease-in-out cursor-pointer
                             checked:bg-primary checked:border-primary
                             focus:ring-2 focus:ring-primary/20 focus:ring-offset-0
                             hover:border-primary/50
                             w-4 h-4 border-2 appearance-none"
                    />
                    <svg 
                      class="absolute w-4 h-4 text-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200 pointer-events-none"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="3"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                  </div>
                </td>
                <td class="py-2 px-4 text-sm">{{ employee.name }}</td>
                <td class="py-2 px-4">
                  <div class="flex flex-wrap gap-1">
                    <span 
                      v-for="role in employee.roles" 
                      :key="role"
                      class="inline-block px-2 py-0.5 rounded-full text-xs"
                      :class="{
                        'bg-blue-100 text-blue-800': role === 'Developer',
                        'bg-purple-100 text-purple-800': role === 'Team Lead',
                        'bg-green-100 text-green-800': role === 'Product Manager',
                        'bg-yellow-100 text-yellow-800': role === 'HR Manager',
                        'bg-pink-100 text-pink-800': role === 'UI/UX Designer'
                      }"
                    >
                      {{ role }}
                    </span>
                  </div>
                </td>
                <td class="py-2 px-4 text-sm">{{ employee.department }}</td>
                <td class="py-2 px-4">
                  <div class="flex flex-col gap-0.5">
                    <div class="flex items-center gap-1 text-xs">
                      <Mail class="w-3.5 h-3.5 text-gray-500" />
                      <a :href="'mailto:' + employee.email" class="text-blue-600 hover:underline">
                        {{ employee.email }}
                      </a>
                    </div>
                    <div class="flex items-center gap-1 text-xs">
                      <Phone class="w-3.5 h-3.5 text-gray-500" />
                      <a :href="'tel:' + employee.phone" class="text-blue-600 hover:underline">
                        {{ employee.phone }}
                      </a>
                    </div>
                  </div>
                </td>
                <td class="py-2 px-4">
                  <span class="inline-block px-2 py-0.5 rounded-full text-xs" 
                    :class="employee.status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                    {{ employee.status }}
                  </span>
                </td>
                <td class="py-2 px-4">
                  <div class="flex justify-center gap-2">
                    <button @click="viewProfile(employee.id)" class="p-1.5 hover:bg-gray-100 rounded" title="View Profile">
                      <Eye class="w-4 h-4 text-gray-600" />
                    </button>
                    <button class="p-1.5 hover:bg-gray-100 rounded" title="Edit">
                      <Pencil class="w-4 h-4 text-blue-600" />
                    </button>
                    <button 
                      @click="confirmDelete(employee)" 
                      class="p-1.5 hover:bg-gray-100 rounded" 
                      title="Delete"
                    >
                      <Trash2 class="w-4 h-4 text-red-600" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Pagination -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mt-6 w-full">
          <div class="flex items-center gap-2">
            <span class="text-sm text-gray-500">Show</span>
            <select 
              v-model="pageSize" 
              @change="changePageSize(Number(pageSize))"
              class="border rounded px-2 py-1 text-sm"
            >
              <option v-for="size in pageSizeOptions" :key="size" :value="size">{{ size }}</option>
            </select>
            <span class="text-sm text-gray-500">entries</span>
          </div>
          <div class="text-xs text-gray-400 sm:text-sm sm:text-gray-500 sm:mx-4">
            Showing {{ (currentPage - 1) * pageSize + 1 }} to {{ Math.min(currentPage * pageSize, filteredEmployees.length) }} of {{ filteredEmployees.length }} entries
          </div>
          <div class="flex gap-2 justify-end">
            <Button 
              size="sm" 
              variant="outline" 
              :disabled="currentPage === 1"
              @click="changePage(currentPage - 1)"
            >
              Previous
            </Button>
            <Button 
              size="sm" 
              variant="outline" 
              :disabled="currentPage === totalPages"
              @click="changePage(currentPage + 1)"
            >
              Next
            </Button>
          </div>
        </div>
      </Card>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">Confirm Delete</h3>
        <p class="text-gray-600 mb-6">
          Are you sure you want to delete {{ employeeToDelete?.name }}? This action cannot be undone.
        </p>
        <div class="flex justify-end gap-3">
          <Button 
            variant="outline" 
            @click="showDeleteModal = false"
          >
            Cancel
          </Button>
          <Button 
            variant="destructive" 
            @click="deleteEmployee"
          >
            Delete
          </Button>
        </div>
      </div>
    </div>
  </AppLayout>
</template> 