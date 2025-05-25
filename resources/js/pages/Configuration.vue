<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import Card from '@/components/ui/card/Card.vue';
import Button from '@/components/ui/button/Button.vue';
import { Settings, Search, Plus, Pencil, Trash2, MoreVertical, CheckCircle, XCircle } from 'lucide-vue-next';
import { ref, computed, onMounted } from 'vue';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter } from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
//  Transition is used in template
// import { Transition } from 'vue';

interface Department {
    id: number;
    name: string;
    description: string;
    date_added: string;
    status: 'active' | 'inactive';
}

interface Role {
    id: number;
    name: string;
    description: string;
    date_added: string;
    status: 'active' | 'inactive';
    color: string;
}

interface AttendanceStatus {
    id: number;
    name: string;
    description: string;
    date_added: string;
    status: 'active' | 'inactive';
    color: string;
}

// Update the roleColors array to include a better custom option
const roleColors = ref([
    { name: 'Blue', value: '#3B82F6' },
    { name: 'Green', value: '#10B981' },
    { name: 'Purple', value: '#8B5CF6' },
    { name: 'Orange', value: '#F97316' },
    { name: 'Pink', value: '#EC4899' },
    { name: 'Teal', value: '#14B8A6' },
    { name: 'Indigo', value: '#6366F1' },
    { name: 'Red', value: '#EF4444' },
    { name: 'Yellow', value: '#F59E0B' },
    { name: 'Custom', value: '', icon: 'palette' } // Added icon for custom option
]);

// Mock data for departments
const departments = ref<Department[]>([
    {
        id: 1,
        name: 'Human Resources',
        description: 'Handles employee relations, recruitment, and HR policies',
        date_added: '2024-03-15',
        status: 'active'
    },
    {
        id: 2,
        name: 'Information Technology',
        description: 'Manages IT infrastructure and technical support',
        date_added: '2024-03-15',
        status: 'active'
    },
    {
        id: 3,
        name: 'Finance',
        description: 'Handles financial operations and accounting',
        date_added: '2024-03-15',
        status: 'active'
    },
    {
        id: 4,
        name: 'Marketing',
        description: 'Manages marketing campaigns and brand strategy',
        date_added: '2024-03-15',
        status: 'inactive'
    },
    {
        id: 5,
        name: 'Operations',
        description: 'Oversees day-to-day business operations',
        date_added: '2024-03-15',
        status: 'active'
    },
]);

// Mock data for roles
const roles = ref<Role[]>([
    {
        id: 1,
        name: 'Administrator',
        description: 'Full system access and control',
        date_added: '2024-03-15',
        status: 'active',
        color: '#3B82F6'
    },
    {
        id: 2,
        name: 'Manager',
        description: 'Department management and reporting',
        date_added: '2024-03-15',
        status: 'active',
        color: '#10B981'
    },
    {
        id: 3,
        name: 'Employee',
        description: 'Basic system access',
        date_added: '2024-03-15',
        status: 'active',
        color: '#8B5CF6'
    },
    {
        id: 4,
        name: 'HR Staff',
        description: 'HR management and employee records',
        date_added: '2024-03-15',
        status: 'active',
        color: '#F97316'
    },
    {
        id: 5,
        name: 'Guest',
        description: 'Limited read-only access',
        date_added: '2024-03-15',
        status: 'inactive',
        color: '#EC4899'
    },
    {
        id: 6,
        name: 'Supervisor',
        description: 'Team supervision and task management',
        date_added: '2024-03-15',
        status: 'active',
        color: '#3B82F6'
    },
    {
        id: 7,
        name: 'Developer',
        description: 'System development and maintenance',
        date_added: '2024-03-15',
        status: 'active',
        color: '#10B981'
    },
    {
        id: 8,
        name: 'Designer',
        description: 'UI/UX design and creative assets',
        date_added: '2024-03-15',
        status: 'active',
        color: '#8B5CF6'
    },
    {
        id: 9,
        name: 'Analyst',
        description: 'Data analysis and reporting',
        date_added: '2024-03-15',
        status: 'active',
        color: '#F97316'
    },
    {
        id: 10,
        name: 'Support',
        description: 'Technical support and troubleshooting',
        date_added: '2024-03-15',
        status: 'active',
        color: '#3B82F6'
    },
    {
        id: 11,
        name: 'Auditor',
        description: 'System audit and compliance',
        date_added: '2024-03-15',
        status: 'inactive',
        color: '#10B981'
    },
    {
        id: 12,
        name: 'Trainer',
        description: 'Employee training and development',
        date_added: '2024-03-15',
        status: 'active',
        color: '#8B5CF6'
    }
]);

// Mock data for attendance statuses
const attendanceStatuses = ref<AttendanceStatus[]>([
    {
        id: 1,
        name: 'Present',
        description: 'Employee is present and working',
        date_added: '2024-03-15',
        status: 'active',
        color: '#10B981' // Green
    },
    {
        id: 2,
        name: 'Absent',
        description: 'Employee is not present',
        date_added: '2024-03-15',
        status: 'active',
        color: '#EF4444' // Red
    },
    {
        id: 3,
        name: 'Late',
        description: 'Employee arrived after scheduled time',
        date_added: '2024-03-15',
        status: 'active',
        color: '#F59E0B' // Yellow
    },
    {
        id: 4,
        name: 'Leave',
        description: 'Employee is on approved leave',
        date_added: '2024-03-15',
        status: 'active',
        color: '#6366F1' // Indigo
    },
    {
        id: 5,
        name: 'Half Day',
        description: 'Employee worked half of the scheduled time',
        date_added: '2024-03-15',
        status: 'active',
        color: '#EC4899' // Pink
    },
    {
        id: 6,
        name: 'Work From Home',
        description: 'Employee is working remotely',
        date_added: '2024-03-15',
        status: 'active',
        color: '#8B5CF6' // Purple
    },
    {
        id: 7,
        name: 'Business Trip',
        description: 'Employee is on official business travel',
        date_added: '2024-03-15',
        status: 'active',
        color: '#F97316' // Orange
    },
    {
        id: 8,
        name: 'Training',
        description: 'Employee is attending training',
        date_added: '2024-03-15',
        status: 'active',
        color: '#14B8A6' // Teal
    }
]);

const search = ref('');
const currentPage = ref(1);
const pageSize = ref(5);
const pageSizeOptions = [5, 10, 20, 50, 100];

// Add status filter refs
const statusFilter = ref<'all' | 'active' | 'inactive'>('all');
const roleStatusFilter = ref<'all' | 'active' | 'inactive'>('all');

const filteredDepartments = computed(() => {
    return departments.value.filter(department => {
        const matchesSearch = department.name.toLowerCase().includes(search.value.toLowerCase()) ||
            department.description.toLowerCase().includes(search.value.toLowerCase());
        const matchesStatus = statusFilter.value === 'all' || department.status === statusFilter.value;
        return matchesSearch && matchesStatus;
    });
});

const paginatedDepartments = computed(() => {
    const start = (currentPage.value - 1) * pageSize.value;
    const end = start + pageSize.value;
    return filteredDepartments.value.slice(start, end);
});

const totalPages = computed(() => Math.ceil(filteredDepartments.value.length / pageSize.value));

function changePage(page: number) {
    currentPage.value = page;
}

function changePageSize(size: number) {
    pageSize.value = size;
    currentPage.value = 1;
}

const selected = ref<number[]>([]);

function toggleSelectAll() {
    if (selected.value.length === filteredDepartments.value.length) {
        selected.value = [];
    } else {
        selected.value = filteredDepartments.value.map(d => d.id);
    }
}

// Add state for delete confirmation modal
const showDeleteModal = ref(false);
const departmentToDelete = ref<Department | null>(null);

// Add delete confirmation function
function deleteDepartment() {
    if (departmentToDelete.value) {
        departments.value = departments.value.filter(d => d.id !== departmentToDelete.value?.id);
        showDeleteModal.value = false;
        departmentToDelete.value = null;
    }
}

// Add state for department form modal
const showDepartmentModal = ref(false);
const isEditing = ref(false);
const departmentForm = ref({
    id: 0,
    name: '',
    description: '',
    date_added: new Date().toISOString().split('T')[0],
    status: 'active' as 'active' | 'inactive'
});

// Reset form function
function resetForm() {
    departmentForm.value = {
        id: 0,
        name: '',
        description: '',
        date_added: new Date().toISOString().split('T')[0],
        status: 'active' as 'active' | 'inactive'
    };
}

// Open modal for adding new department
function handleAddDepartment() {
    isEditing.value = false;
    resetForm();
    showDepartmentModal.value = true;
}

// Open modal for editing department
function handleEdit(department: Department) {
    isEditing.value = true;
    departmentForm.value = { ...department };
    showDepartmentModal.value = true;
}

// Save department (add or edit)
function saveDepartment() {
    if (isEditing.value) {
        // Update existing department
        const index = departments.value.findIndex(d => d.id === departmentForm.value.id);
        if (index !== -1) {
            departments.value[index] = { ...departmentForm.value };
        }
    } else {
        // Add new department
        const newDepartment = {
            ...departmentForm.value,
            id: Math.max(...departments.value.map(d => d.id)) + 1
        };
        departments.value.push(newDepartment);
    }
    showDepartmentModal.value = false;
}

// Add state for roles
const roleSearch = ref('');
const roleCurrentPage = ref(1);
const rolePageSize = ref(5);
const selectedRoles = ref<number[]>([]);
const showRoleModal = ref(false);
const isEditingRole = ref(false);
const roleForm = ref({
    id: 0,
    name: '',
    description: '',
    date_added: new Date().toISOString().split('T')[0],
    status: 'active' as 'active' | 'inactive',
    color: '#3B82F6'
});

// Add state for custom color input
const showCustomColorInput = ref(false);
const customColor = ref('#000000');

// Add a computed property to check if current color is custom
const isCustomColor = computed(() => {
    return !roleColors.value.some(color => color.value === roleForm.value.color && color.name !== 'Custom');
});

// Function to handle color selection
function handleColorSelect(color: string) {
    if (color === '') {
        // If clicking the custom color button, just toggle the panel
        showCustomColorInput.value = !showCustomColorInput.value;
    } else {
        // If selecting a predefined color, update the color and close the panel
        roleForm.value.color = color;
        showCustomColorInput.value = false;
    }
}

// Update the custom color input handlers
function handleCustomColorChange(event: Event) {
    const input = event.target as HTMLInputElement;
    customColor.value = input.value;
    roleForm.value.color = input.value; // Apply color immediately
}

// Computed properties for roles
const filteredRoles = computed(() => {
    return roles.value.filter(role => {
        const matchesSearch = role.name.toLowerCase().includes(roleSearch.value.toLowerCase()) ||
            role.description.toLowerCase().includes(roleSearch.value.toLowerCase());
        const matchesStatus = roleStatusFilter.value === 'all' || role.status === roleStatusFilter.value;
        return matchesSearch && matchesStatus;
    });
});

const paginatedRoles = computed(() => {
    const start = (roleCurrentPage.value - 1) * rolePageSize.value;
    const end = start + rolePageSize.value;
    return filteredRoles.value.slice(start, end);
});

const roleTotalPages = computed(() => Math.ceil(filteredRoles.value.length / rolePageSize.value));

// Functions for roles
function changeRolePage(page: number) {
    roleCurrentPage.value = page;
}

function changeRolePageSize(size: number) {
    rolePageSize.value = size;
    roleCurrentPage.value = 1;
}

function toggleSelectAllRoles() {
    if (selectedRoles.value.length === filteredRoles.value.length) {
        selectedRoles.value = [];
    } else {
        selectedRoles.value = filteredRoles.value.map(r => r.id);
    }
}

function resetRoleForm() {
    roleForm.value = {
        id: 0,
        name: '',
        description: '',
        date_added: new Date().toISOString().split('T')[0],
        status: 'active' as 'active' | 'inactive',
        color: '#3B82F6'
    };
    showCustomColorInput.value = false;
    customColor.value = '#000000';
}

function handleAddRole() {
    isEditingRole.value = false;
    resetRoleForm();
    showRoleModal.value = true;
}

function handleEditRole(role: Role) {
    isEditingRole.value = true;
    roleForm.value = { ...role };
    // Check if the role's color is one of the predefined colors
    const isPredefinedColor = roleColors.value.some(color => color.value === role.color && color.name !== 'Custom');
    if (!isPredefinedColor) {
        // If it's a custom color, ensure the custom color input is shown
        showCustomColorInput.value = true;
        customColor.value = role.color;
    }
    showRoleModal.value = true;
}

function saveRole() {
    if (isEditingRole.value) {
        const index = roles.value.findIndex(r => r.id === roleForm.value.id);
        if (index !== -1) {
            roles.value[index] = { ...roleForm.value };
        }
    } else {
        const newRole = {
            ...roleForm.value,
            id: Math.max(...roles.value.map(r => r.id)) + 1
        };
        roles.value.push(newRole);
    }
    showRoleModal.value = false;
}

// Add state for role delete confirmation
const showRoleDeleteModal = ref(false);
const roleToDelete = ref<Role | null>(null);

function confirmDeleteRole(role: Role) {
    roleToDelete.value = role;
    showRoleDeleteModal.value = true;
}

function deleteRole() {
    if (roleToDelete.value) {
        roles.value = roles.value.filter(r => r.id !== roleToDelete.value?.id);
        showRoleDeleteModal.value = false;
        roleToDelete.value = null;
    }
}

// Add bulk status update functions
// function updateSelectedRoleStatus(status: 'active' | 'inactive') {
//     roles.value = roles.value.map(role => {
//         if (selectedRoles.value.includes(role.id)) {
//             return { ...role, status };
//         }
//         return role;
//     });
//     selectedRoles.value = [];
// }

// Update table filter ref to include 'all'
const activeTable = ref<'all' | 'departments' | 'roles' | 'attendance'>('all');

// Add functions for individual status updates
function updateRoleStatus(role: Role, status: 'active' | 'inactive') {
    const index = roles.value.findIndex(r => r.id === role.id);
    if (index !== -1) {
        roles.value[index] = { ...role, status };
    }
}

// Update the state management for action menus
const activeMenu = ref<{ type: 'role' | 'attendance' | null, id: number | null }>({ type: null, id: null });

// Add state for menu position
const menuPosition = ref({ x: 0, y: 0 });

// Update the toggle functions to use the unified menu state
function toggleRoleMenu(id: number, event: MouseEvent) {
    if (activeMenu.value.type === 'role' && activeMenu.value.id === id) {
        activeMenu.value = { type: null, id: null };
        return;
    }
    
    const button = event.currentTarget as HTMLElement;
    const rect = button.getBoundingClientRect();
    const spaceBelow = window.innerHeight - rect.bottom;
    const spaceAbove = rect.top;
    
    menuPosition.value = {
        x: rect.right - 192,
        y: spaceBelow < 200 && spaceAbove > 200 ? rect.top - 200 : rect.bottom
    };
    
    activeMenu.value = { type: 'role', id };
}

function toggleAttendanceStatusMenu(id: number, event: MouseEvent) {
    if (activeMenu.value.type === 'attendance' && activeMenu.value.id === id) {
        activeMenu.value = { type: null, id: null };
        return;
    }
    
    const button = event.currentTarget as HTMLElement;
    const rect = button.getBoundingClientRect();
    const spaceBelow = window.innerHeight - rect.bottom;
    const spaceAbove = rect.top;
    
    menuPosition.value = {
        x: rect.right - 192,
        y: spaceBelow < 200 && spaceAbove > 200 ? rect.top - 200 : rect.bottom
    };
    
    activeMenu.value = { type: 'attendance', id };
}

// Add scroll event handling
onMounted(() => {
    // Existing click handler
    document.addEventListener('click', (e) => {
        const target = e.target as HTMLElement;
        if (!target.closest('.action-menu-trigger')) {
            activeMenu.value = { type: null, id: null };
        }
    });

    // Add scroll handler
    document.addEventListener('scroll', () => {
        activeMenu.value = { type: null, id: null };
    }, true); // Use capture phase to catch all scroll events
});

// Add bulk action functions for departments
function updateDepartmentStatusSelected(status: 'active' | 'inactive') {
    departments.value = departments.value.map(d => selected.value.includes(d.id) ? { ...d, status } : d);
    selected.value = [];
}

// Add bulk action functions for roles
function updateRoleStatusSelected(status: 'active' | 'inactive') {
    roles.value = roles.value.map(r => selectedRoles.value.includes(r.id) ? { ...r, status } : r);
    selectedRoles.value = [];
}

// Add state for attendance status
const attendanceSearch = ref('');
const attendanceCurrentPage = ref(1);
const attendancePageSize = ref(5);
const selectedAttendanceStatuses = ref<number[]>([]);
const showAttendanceStatusModal = ref(false);
const isEditingAttendanceStatus = ref(false);
const attendanceStatusForm = ref({
    id: 0,
    name: '',
    description: '',
    date_added: new Date().toISOString().split('T')[0],
    status: 'active' as 'active' | 'inactive',
    color: '#10B981'
});

// Add state for attendance status delete confirmation
const showAttendanceStatusDeleteModal = ref(false);
const attendanceStatusToDelete = ref<AttendanceStatus | null>(null);

// Add state for custom color input for attendance status
const showAttendanceCustomColorInput = ref(false);
const attendanceCustomColor = ref('#000000');

// Add a computed property to check if current color is custom for attendance status
const isAttendanceCustomColor = computed(() => {
    return !roleColors.value.some(color => color.value === attendanceStatusForm.value.color && color.name !== 'Custom');
});

// Function to handle color selection for attendance status
function handleAttendanceColorSelect(color: string) {
    if (color === '') {
        showAttendanceCustomColorInput.value = !showAttendanceCustomColorInput.value;
    } else {
        attendanceStatusForm.value.color = color;
        showAttendanceCustomColorInput.value = false;
    }
}

// Update the custom color input handlers for attendance status
function handleAttendanceCustomColorChange(event: Event) {
    const input = event.target as HTMLInputElement;
    attendanceCustomColor.value = input.value;
    attendanceStatusForm.value.color = input.value;
}

// Computed properties for attendance statuses
const filteredAttendanceStatuses = computed(() => {
    return attendanceStatuses.value.filter(status => {
        const matchesSearch = status.name.toLowerCase().includes(attendanceSearch.value.toLowerCase()) ||
            status.description.toLowerCase().includes(attendanceSearch.value.toLowerCase());
        const matchesStatus = attendanceStatusFilter.value === 'all' || status.status === attendanceStatusFilter.value;
        return matchesSearch && matchesStatus;
    });
});

const paginatedAttendanceStatuses = computed(() => {
    const start = (attendanceCurrentPage.value - 1) * attendancePageSize.value;
    const end = start + attendancePageSize.value;
    return filteredAttendanceStatuses.value.slice(start, end);
});

const attendanceTotalPages = computed(() => Math.ceil(filteredAttendanceStatuses.value.length / attendancePageSize.value));

// Add status filter ref for attendance status
const attendanceStatusFilter = ref<'all' | 'active' | 'inactive'>('all');

// Functions for attendance status
function changeAttendancePage(page: number) {
    attendanceCurrentPage.value = page;
}

function changeAttendancePageSize(size: number) {
    attendancePageSize.value = size;
    attendanceCurrentPage.value = 1;
}

function toggleSelectAllAttendanceStatuses() {
    if (selectedAttendanceStatuses.value.length === filteredAttendanceStatuses.value.length) {
        selectedAttendanceStatuses.value = [];
    } else {
        selectedAttendanceStatuses.value = filteredAttendanceStatuses.value.map(s => s.id);
    }
}

function resetAttendanceStatusForm() {
    attendanceStatusForm.value = {
        id: 0,
        name: '',
        description: '',
        date_added: new Date().toISOString().split('T')[0],
        status: 'active' as 'active' | 'inactive',
        color: '#10B981'
    };
    showAttendanceCustomColorInput.value = false;
    attendanceCustomColor.value = '#000000';
}

function handleAddAttendanceStatus() {
    isEditingAttendanceStatus.value = false;
    resetAttendanceStatusForm();
    showAttendanceStatusModal.value = true;
}

function handleEditAttendanceStatus(status: AttendanceStatus) {
    isEditingAttendanceStatus.value = true;
    attendanceStatusForm.value = { ...status };
    // Check if the status's color is one of the predefined colors
    const isPredefinedColor = roleColors.value.some(color => color.value === status.color && color.name !== 'Custom');
    if (!isPredefinedColor) {
        showAttendanceCustomColorInput.value = true;
        attendanceCustomColor.value = status.color;
    }
    showAttendanceStatusModal.value = true;
}

function saveAttendanceStatus() {
    if (isEditingAttendanceStatus.value) {
        const index = attendanceStatuses.value.findIndex(s => s.id === attendanceStatusForm.value.id);
        if (index !== -1) {
            attendanceStatuses.value[index] = { ...attendanceStatusForm.value };
        }
    } else {
        const newStatus = {
            ...attendanceStatusForm.value,
            id: Math.max(...attendanceStatuses.value.map(s => s.id)) + 1
        };
        attendanceStatuses.value.push(newStatus);
    }
    showAttendanceStatusModal.value = false;
}

function confirmDeleteAttendanceStatus(status: AttendanceStatus) {
    attendanceStatusToDelete.value = status;
    showAttendanceStatusDeleteModal.value = true;
}

function deleteAttendanceStatus() {
    if (attendanceStatusToDelete.value) {
        attendanceStatuses.value = attendanceStatuses.value.filter(s => s.id !== attendanceStatusToDelete.value?.id);
        showAttendanceStatusDeleteModal.value = false;
        attendanceStatusToDelete.value = null;
    }
}

function updateAttendanceStatus(status: AttendanceStatus, newStatus: 'active' | 'inactive') {
    const index = attendanceStatuses.value.findIndex(s => s.id === status.id);
    if (index !== -1) {
        attendanceStatuses.value[index] = { ...status, status: newStatus };
    }
}

function updateAttendanceStatusSelected(status: 'active' | 'inactive') {
    attendanceStatuses.value = attendanceStatuses.value.map(s => 
        selectedAttendanceStatuses.value.includes(s.id) ? { ...s, status } : s
    );
    selectedAttendanceStatuses.value = [];
}

// Update the state management for attendance status menu
// const activeAttendanceStatusMenu = ref<number | null>(null);

// Update the toggle function for attendance status menu
</script>

<template>
    <Head title="Configuration" />
    <AppLayout :breadcrumbs="[{ title: 'Configuration', href: '/configuration' }]">
        <div class="flex flex-col gap-6 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <Settings class="w-6 h-6" /> Configuration
                </h1>
                <div class="relative">
                <select 
                    v-model="activeTable"
                        class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300 ease-in-out hover:border-primary/50 transform hover:scale-[1.02]"
                >
                    <option value="all">All Configurations</option>
                    <option value="departments">Departments</option>
                    <option value="roles">Roles</option>
                        <option value="attendance">Attendance Status</option>
                </select>
                    <!-- Enhanced highlight effect with smoother transition -->
                    <div 
                        class="absolute inset-0 rounded-lg pointer-events-none transition-all duration-500 ease-in-out transform"
                        :class="[
                            'bg-primary/5',
                            activeTable !== 'all' ? 'opacity-100 scale-100' : 'opacity-0 scale-95'
                        ]"
                    ></div>
                </div>
            </div>
            <div class="flex flex-col gap-6">
                <!-- Enhanced transitions for cards -->
                <Transition
                    enter-active-class="transition-all duration-500 ease-out"
                    enter-from-class="opacity-0 -translate-y-8 scale-95"
                    enter-to-class="opacity-100 translate-y-0 scale-100"
                    leave-active-class="transition-all duration-300 ease-in"
                    leave-from-class="opacity-100 translate-y-0 scale-100"
                    leave-to-class="opacity-0 -translate-y-8 scale-95"
                    mode="out-in"
                >
                    <Card v-show="activeTable === 'all' || activeTable === 'departments'" class="p-4 transform transition-all duration-300 ease-in-out hover:shadow-lg">
                    <div class="flex flex-col md:flex-row md:items-center gap-3 mb-4">
                        <h2 class="text-lg font-semibold flex-1 mb-2 md:mb-0">Departments</h2>
                        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                            <div class="relative flex-1 min-w-[180px]">
                                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
                                <input 
                                    v-model="search" 
                                    type="text" 
                                    placeholder="Search departments..." 
                                    class="w-full pl-10 pr-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary h-[44px]"
                                />
                            </div>
                            <select 
                                v-model="statusFilter"
                                class="min-w-[180px] px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary h-[44px]"
                            >
                                <option value="all">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <Button @click="handleAddDepartment" class="flex items-center gap-2 h-[44px]">
                                <Plus class="h-4 w-4" />
                                Add
                            </Button>
                        </div>
                    </div>

                    <!-- Bulk Actions for Departments -->
                    <Transition
                        enter-active-class="transition-all duration-200 ease-out"
                        enter-from-class="opacity-0 -translate-y-2"
                        enter-to-class="opacity-100 translate-y-0"
                        leave-active-class="transition-all duration-150 ease-in"
                        leave-from-class="opacity-100 translate-y-0"
                        leave-to-class="opacity-0 -translate-y-2"
                    >
                        <div v-if="selected.length > 0" class="mb-4 p-2 bg-gray-50 rounded-lg flex items-center gap-2">
                            <span class="text-sm text-gray-600">{{ selected.length }} selected</span>
                            <div class="flex gap-2">
                                <Button 
                                    size="sm" 
                                    variant="outline"
                                    @click="updateDepartmentStatusSelected('active')"
                                    class="transition-colors duration-200 text-green-600 hover:text-green-700 hover:bg-green-50"
                                >
                                    <CheckCircle class="w-4 h-4 mr-1" />
                                    Set Active
                                </Button>
                                <Button 
                                    size="sm" 
                                    variant="outline"
                                    @click="updateDepartmentStatusSelected('inactive')"
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
                                                :checked="selected.length === filteredDepartments.length"
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
                                    <th class="text-left py-2 px-4 text-sm font-medium">Department Name</th>
                                    <th class="text-left py-2 px-4 text-sm font-medium">Status</th>
                                    <th class="text-left py-2 px-4 text-sm font-medium">Date Added</th>
                                    <th class="text-center py-2 px-4 text-sm font-medium">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr 
                                    v-for="department in paginatedDepartments" 
                                    :key="department.id" 
                                    class="border-b transition-colors duration-200 ease-in-out"
                                    :class="[
                                        selected.includes(department.id) 
                                            ? 'bg-primary/5 hover:bg-primary/10' 
                                            : 'hover:bg-gray-50'
                                    ]"
                                >
                                    <td class="text-center py-2 px-4">
                                        <div class="relative inline-flex items-center">
                                            <input 
                                                type="checkbox" 
                                                :checked="selected.includes(department.id)"
                                                @change="selected.includes(department.id) ? selected = selected.filter(id => id !== department.id) : selected.push(department.id)"
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
                                    <td class="py-2 px-4 text-sm">{{ department.name }}</td>
                                    <td class="py-2 px-4 text-sm">
                                        <span 
                                            :class="[
                                                'px-2 py-1 rounded-full text-xs font-medium',
                                                department.status === 'active' 
                                                    ? 'bg-green-100 text-green-800' 
                                                    : 'bg-gray-100 text-gray-800'
                                            ]"
                                        >
                                            {{ department.status.charAt(0).toUpperCase() + department.status.slice(1) }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 text-sm">{{ new Date(department.date_added).toLocaleDateString() }}</td>
                                    <td class="py-2 px-4">
                                        <div class="flex justify-center gap-2">
                                            <div class="relative">
                                                <button 
                                                    @click="handleEdit(department)"
                                                    class="p-1.5 hover:bg-gray-100 rounded" 
                                                    title="Edit"
                                                >
                                                    <Pencil class="w-4 h-4 text-blue-600" />
                                                </button>
                                            </div>
                                            <div class="relative">
                                                <button 
                                                    class="action-menu-trigger p-1.5 hover:bg-gray-100 rounded" 
                                                    title="More Actions"
                                                        @click.stop="toggleRoleMenu(department.id, $event)"
                                                >
                                                    <MoreVertical class="w-4 h-4 text-gray-600" />
                                                </button>
                                            </div>
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
                        <div class="text-sm text-gray-500">
                            Showing {{ (currentPage - 1) * pageSize + 1 }} to {{ Math.min(currentPage * pageSize, filteredDepartments.length) }} of {{ filteredDepartments.length }} entries
                        </div>
                        <div class="flex gap-2">
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
                </Transition>

                <Transition
                    enter-active-class="transition-all duration-500 ease-out"
                    enter-from-class="opacity-0 -translate-y-8 scale-95"
                    enter-to-class="opacity-100 translate-y-0 scale-100"
                    leave-active-class="transition-all duration-300 ease-in"
                    leave-from-class="opacity-100 translate-y-0 scale-100"
                    leave-to-class="opacity-0 -translate-y-8 scale-95"
                    mode="out-in"
                >
                    <Card v-show="activeTable === 'all' || activeTable === 'roles'" class="p-4 transform transition-all duration-300 ease-in-out hover:shadow-lg">
                    <div class="flex flex-col md:flex-row md:items-center gap-3 mb-4">
                        <h2 class="text-lg font-semibold flex-1 mb-2 md:mb-0">Roles</h2>
                        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                            <div class="relative flex-1 min-w-[180px]">
                                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
                                <input 
                                    v-model="roleSearch" 
                                    type="text" 
                                    placeholder="Search roles..." 
                                    class="w-full pl-10 pr-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary h-[44px]"
                                />
                            </div>
                            <select 
                                v-model="roleStatusFilter"
                                class="min-w-[180px] px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary h-[44px]"
                            >
                                <option value="all">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <Button @click="handleAddRole" class="flex items-center gap-2 h-[44px]">
                                <Plus class="h-4 w-4" />
                                Add
                            </Button>
                        </div>
                    </div>

                    <!-- Bulk Actions for Roles -->
                    <Transition
                        enter-active-class="transition-all duration-200 ease-out"
                        enter-from-class="opacity-0 -translate-y-2"
                        enter-to-class="opacity-100 translate-y-0"
                        leave-active-class="transition-all duration-150 ease-in"
                        leave-from-class="opacity-100 translate-y-0"
                        leave-to-class="opacity-0 -translate-y-2"
                    >
                        <div v-if="selectedRoles.length > 0" class="mb-4 p-2 bg-gray-50 rounded-lg flex items-center gap-2">
                            <span class="text-sm text-gray-600">{{ selectedRoles.length }} selected</span>
                            <div class="flex gap-2">
                                <Button 
                                    size="sm" 
                                    variant="outline"
                                    @click="updateRoleStatusSelected('active')"
                                    class="transition-colors duration-200 text-green-600 hover:text-green-700 hover:bg-green-50"
                                >
                                    <CheckCircle class="w-4 h-4 mr-1" />
                                    Set Active
                                </Button>
                                <Button 
                                    size="sm" 
                                    variant="outline"
                                    @click="updateRoleStatusSelected('inactive')"
                                    class="transition-colors duration-200 text-red-600 hover:text-red-700 hover:bg-red-50"
                                >
                                    <XCircle class="w-4 h-4 mr-1" />
                                    Set Inactive
                                </Button>
                                <Button 
                                    size="sm" 
                                    variant="outline"
                                    @click="selectedRoles = []"
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
                                                :checked="selectedRoles.length === filteredRoles.length"
                                                @change="toggleSelectAllRoles"
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
                                    <th class="text-left py-2 px-4 text-sm font-medium">Role Name</th>
                                        <th class="text-left py-2 px-4 text-sm font-medium">Color</th>
                                    <th class="text-left py-2 px-4 text-sm font-medium">Status</th>
                                    <th class="text-left py-2 px-4 text-sm font-medium">Date Added</th>
                                    <th class="text-center py-2 px-4 text-sm font-medium">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr 
                                    v-for="role in paginatedRoles" 
                                    :key="role.id" 
                                    class="border-b transition-colors duration-200 ease-in-out"
                                    :class="[
                                        selectedRoles.includes(role.id) 
                                            ? 'bg-primary/5 hover:bg-primary/10' 
                                            : 'hover:bg-gray-50'
                                    ]"
                                >
                                    <td class="text-center py-2 px-4">
                                        <div class="relative inline-flex items-center">
                                            <input 
                                                type="checkbox" 
                                                :checked="selectedRoles.includes(role.id)"
                                                @change="selectedRoles.includes(role.id) ? selectedRoles = selectedRoles.filter(id => id !== role.id) : selectedRoles.push(role.id)"
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
                                    <td class="py-2 px-4 text-sm">{{ role.name }}</td>
                                        <td class="py-2 px-4 text-sm">
                                            <div class="flex items-center gap-2">
                                                <div 
                                                    class="w-6 h-6 rounded-full border border-gray-200"
                                                    :style="{ backgroundColor: role.color }"
                                                ></div>
                                                <span class="text-xs text-gray-500">{{ role.color }}</span>
                                            </div>
                                        </td>
                                    <td class="py-2 px-4 text-sm">
                                        <span 
                                            :class="[
                                                'px-2 py-1 rounded-full text-xs font-medium',
                                                role.status === 'active' 
                                                    ? 'bg-green-100 text-green-800' 
                                                    : 'bg-gray-100 text-gray-800'
                                            ]"
                                        >
                                            {{ role.status.charAt(0).toUpperCase() + role.status.slice(1) }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 text-sm">{{ new Date(role.date_added).toLocaleDateString() }}</td>
                                    <td class="py-2 px-4">
                                        <div class="flex justify-center gap-2">
                                            <div class="relative">
                                                <button 
                                                    @click="handleEditRole(role)"
                                                    class="p-1.5 hover:bg-gray-100 rounded" 
                                                    title="Edit"
                                                >
                                                    <Pencil class="w-4 h-4 text-blue-600" />
                                                </button>
                                            </div>
                                            <div class="relative">
                                                <button 
                                                    class="action-menu-trigger p-1.5 hover:bg-gray-100 rounded" 
                                                    title="More Actions"
                                                    @click.stop="toggleRoleMenu(role.id, $event)"
                                                >
                                                    <MoreVertical class="w-4 h-4 text-gray-600" />
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination for Roles -->
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mt-6 w-full">
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">Show</span>
                            <select 
                                v-model="rolePageSize" 
                                @change="changeRolePageSize(Number(rolePageSize))"
                                class="border rounded px-2 py-1 text-sm h-[36px]"
                            >
                                <option v-for="size in pageSizeOptions" :key="size" :value="size">{{ size }}</option>
                            </select>
                            <span class="text-sm text-gray-500">entries</span>
                        </div>
                        <div class="text-xs text-gray-400 sm:text-sm sm:text-gray-500 text-left">
                            Showing {{ (roleCurrentPage - 1) * rolePageSize + 1 }} to {{ Math.min(roleCurrentPage * rolePageSize, filteredRoles.length) }} of {{ filteredRoles.length }} entries
                        </div>
                        <div class="flex gap-2 justify-end">
                            <Button 
                                size="sm" 
                                variant="outline" 
                                :disabled="roleCurrentPage === 1"
                                @click="changeRolePage(roleCurrentPage - 1)"
                            >
                                Previous
                            </Button>
                            <Button 
                                size="sm" 
                                variant="outline" 
                                :disabled="roleCurrentPage === roleTotalPages"
                                @click="changeRolePage(roleCurrentPage + 1)"
                            >
                                Next
                            </Button>
                        </div>
                    </div>
                </Card>
                </Transition>

                <Transition
                    enter-active-class="transition-all duration-500 ease-out"
                    enter-from-class="opacity-0 -translate-y-8 scale-95"
                    enter-to-class="opacity-100 translate-y-0 scale-100"
                    leave-active-class="transition-all duration-300 ease-in"
                    leave-from-class="opacity-100 translate-y-0 scale-100"
                    leave-to-class="opacity-0 -translate-y-8 scale-95"
                    mode="out-in"
                >
                    <Card v-show="activeTable === 'all' || activeTable === 'attendance'" class="p-4 transform transition-all duration-300 ease-in-out hover:shadow-lg">
                        <div class="flex flex-col md:flex-row md:items-center gap-3 mb-4">
                            <h2 class="text-lg font-semibold flex-1 mb-2 md:mb-0">Attendance Status</h2>
                            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                                <div class="relative flex-1 min-w-[180px]">
                                    <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
                                    <input 
                                        v-model="attendanceSearch" 
                                        type="text" 
                                        placeholder="Search attendance status..." 
                                        class="w-full pl-10 pr-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary h-[44px]"
                                    />
                                </div>
                                <select 
                                    v-model="attendanceStatusFilter"
                                    class="min-w-[180px] px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary h-[44px]"
                                >
                                    <option value="all">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <Button @click="handleAddAttendanceStatus" class="flex items-center gap-2 h-[44px]">
                                    <Plus class="h-4 w-4" />
                                    Add
                                </Button>
                            </div>
                        </div>

                        <!-- Bulk Actions for Attendance Status -->
                        <Transition
                            enter-active-class="transition-all duration-200 ease-out"
                            enter-from-class="opacity-0 -translate-y-2"
                            enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition-all duration-150 ease-in"
                            leave-from-class="opacity-100 translate-y-0"
                            leave-to-class="opacity-0 -translate-y-2"
                        >
                            <div v-if="selectedAttendanceStatuses.length > 0" class="mb-4 p-2 bg-gray-50 rounded-lg flex items-center gap-2">
                                <span class="text-sm text-gray-600">{{ selectedAttendanceStatuses.length }} selected</span>
                                <div class="flex gap-2">
                                    <Button 
                                        size="sm" 
                                        variant="outline"
                                        @click="updateAttendanceStatusSelected('active')"
                                        class="transition-colors duration-200 text-green-600 hover:text-green-700 hover:bg-green-50"
                                    >
                                        <CheckCircle class="w-4 h-4 mr-1" />
                                        Set Active
                                    </Button>
                                    <Button 
                                        size="sm" 
                                        variant="outline"
                                        @click="updateAttendanceStatusSelected('inactive')"
                                        class="transition-colors duration-200 text-red-600 hover:text-red-700 hover:bg-red-50"
                                    >
                                        <XCircle class="w-4 h-4 mr-1" />
                                        Set Inactive
                                    </Button>
                                    <Button 
                                        size="sm" 
                                        variant="outline"
                                        @click="selectedAttendanceStatuses = []"
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
                                                    :checked="selectedAttendanceStatuses.length === filteredAttendanceStatuses.length"
                                                    @change="toggleSelectAllAttendanceStatuses"
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
                                        <th class="text-left py-2 px-4 text-sm font-medium">Status Name</th>
                                        <th class="text-left py-2 px-4 text-sm font-medium">Color</th>
                                        <th class="text-left py-2 px-4 text-sm font-medium">Status</th>
                                        <th class="text-left py-2 px-4 text-sm font-medium">Date Added</th>
                                        <th class="text-center py-2 px-4 text-sm font-medium">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr 
                                        v-for="status in paginatedAttendanceStatuses" 
                                        :key="status.id" 
                                        class="border-b transition-colors duration-200 ease-in-out"
                                        :class="[
                                            selectedAttendanceStatuses.includes(status.id) 
                                                ? 'bg-primary/5 hover:bg-primary/10' 
                                                : 'hover:bg-gray-50'
                                        ]"
                                    >
                                        <td class="text-center py-2 px-4">
                                            <div class="relative inline-flex items-center">
                                                <input 
                                                    type="checkbox" 
                                                    :checked="selectedAttendanceStatuses.includes(status.id)"
                                                    @change="selectedAttendanceStatuses.includes(status.id) ? selectedAttendanceStatuses = selectedAttendanceStatuses.filter(id => id !== status.id) : selectedAttendanceStatuses.push(status.id)"
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
                                        <td class="py-2 px-4 text-sm">{{ status.name }}</td>
                                        <td class="py-2 px-4 text-sm">
                                            <div class="flex items-center gap-2">
                                                <div 
                                                    class="w-6 h-6 rounded-full border border-gray-200"
                                                    :style="{ backgroundColor: status.color }"
                                                ></div>
                                                <span class="text-xs text-gray-500">{{ status.color }}</span>
                                            </div>
                                        </td>
                                        <td class="py-2 px-4 text-sm">
                                            <span 
                                                :class="[
                                                    'px-2 py-1 rounded-full text-xs font-medium',
                                                    status.status === 'active' 
                                                        ? 'bg-green-100 text-green-800' 
                                                        : 'bg-gray-100 text-gray-800'
                                                ]"
                                            >
                                                {{ status.status.charAt(0).toUpperCase() + status.status.slice(1) }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4 text-sm">{{ new Date(status.date_added).toLocaleDateString() }}</td>
                                        <td class="py-2 px-4">
                                            <div class="flex justify-center gap-2">
                                                <div class="relative">
                                                    <button 
                                                        @click="handleEditAttendanceStatus(status)"
                                                        class="p-1.5 hover:bg-gray-100 rounded" 
                                                        title="Edit"
                                                    >
                                                        <Pencil class="w-4 h-4 text-blue-600" />
                                                    </button>
                                                </div>
                                                <div class="relative">
                                                    <button 
                                                        class="action-menu-trigger p-1.5 hover:bg-gray-100 rounded" 
                                                        title="More Actions"
                                                        @click.stop="toggleAttendanceStatusMenu(status.id, $event)"
                                                    >
                                                        <MoreVertical class="w-4 h-4 text-gray-600" />
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination for Attendance Status -->
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mt-6 w-full">
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-500">Show</span>
                                <select 
                                    v-model="attendancePageSize" 
                                    @change="changeAttendancePageSize(Number(attendancePageSize))"
                                    class="border rounded px-2 py-1 text-sm h-[36px]"
                                >
                                    <option v-for="size in pageSizeOptions" :key="size" :value="size">{{ size }}</option>
                                </select>
                                <span class="text-sm text-gray-500">entries</span>
                            </div>
                            <div class="text-xs text-gray-400 sm:text-sm sm:text-gray-500 text-left">
                                Showing {{ (attendanceCurrentPage - 1) * attendancePageSize + 1 }} to {{ Math.min(attendanceCurrentPage * attendancePageSize, filteredAttendanceStatuses.length) }} of {{ filteredAttendanceStatuses.length }} entries
                            </div>
                            <div class="flex gap-2 justify-end">
                                <Button 
                                    size="sm" 
                                    variant="outline" 
                                    :disabled="attendanceCurrentPage === 1"
                                    @click="changeAttendancePage(attendanceCurrentPage - 1)"
                                >
                                    Previous
                                </Button>
                                <Button 
                                    size="sm" 
                                    variant="outline" 
                                    :disabled="attendanceCurrentPage === attendanceTotalPages"
                                    @click="changeAttendancePage(attendanceCurrentPage + 1)"
                                >
                                    Next
                                </Button>
                            </div>
                        </div>
                    </Card>
                </Transition>
            </div>
        </div>

        <!-- Department Form Modal -->
        <Dialog v-model:open="showDepartmentModal">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{{ isEditing ? 'Edit Department' : 'Add Department' }}</DialogTitle>
                    <DialogDescription>
                        {{ isEditing ? 'Update the department details below.' : 'Fill in the details below to add a new department.' }}
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="saveDepartment" class="grid gap-4 py-4 pb-0">
                    <div class="grid gap-2">
                        <Label for="name">Department Name</Label>
                        <Input
                            id="name"
                            v-model="departmentForm.name"
                            placeholder="Enter department name"
                            required
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="description">Description</Label>
                        <Textarea
                            id="description"
                            v-model="departmentForm.description"
                            placeholder="Enter department description"
                            required
                            :rows="3"
                        />
                    </div>
                    <DialogFooter>
                        <Button
                            type="button"
                            variant="outline"
                            @click="showDepartmentModal = false"
                        >
                            Cancel
                        </Button>
                        <Button 
                            type="submit"
                            :disabled="!departmentForm.name || !departmentForm.description"
                        >
                            {{ isEditing ? 'Save Changes' : 'Add Department' }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Delete Confirmation Modal -->
        <Dialog v-model:open="showDeleteModal">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Delete Department</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete the department "{{ departmentToDelete?.name }}"? This action cannot be undone.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="mt-6">
                    <Button 
                        variant="outline" 
                        @click="showDeleteModal = false"
                    >
                        Cancel
                    </Button>
                    <Button 
                        variant="destructive" 
                        @click="deleteDepartment"
                    >
                        Delete
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Role Form Modal -->
        <Dialog v-model:open="showRoleModal">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{{ isEditingRole ? 'Edit Role' : 'Add Role' }}</DialogTitle>
                    <DialogDescription>
                        {{ isEditingRole ? 'Update the role details below.' : 'Fill in the details below to add a new role.' }}
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="saveRole" class="grid gap-4 py-4 pb-0">
                    <div class="grid gap-2">
                        <Label for="roleName">Role Name</Label>
                        <Input
                            id="roleName"
                            v-model="roleForm.name"
                            placeholder="Enter role name"
                            required
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="roleDescription">Description</Label>
                        <Textarea
                            id="roleDescription"
                            v-model="roleForm.description"
                            placeholder="Enter role description"
                            required
                            :rows="3"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="roleStatus">Status</Label>
                        <select
                            id="roleStatus"
                            v-model="roleForm.status"
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
                        >
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="grid gap-2">
                        <Label for="roleColor">Role Color</Label>
                        <div class="flex flex-col gap-3">
                            <div class="flex flex-wrap gap-2">
                                <button
                                    v-for="color in roleColors"
                                    :key="color.value"
                                    type="button"
                                    class="relative w-9 h-9 rounded-full border-2 transition-all duration-200 group"
                                    :class="[
                                        roleForm.color === color.value ? 'border-primary scale-110 ring-2 ring-primary/20' : 'border-gray-200 hover:border-gray-300',
                                        color.name === 'Custom' ? (isCustomColor ? '' : 'bg-gradient-to-br from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200') : ''
                                    ]"
                                    :style="color.name === 'Custom' && isCustomColor ? { backgroundColor: roleForm.color } : (color.name !== 'Custom' ? { backgroundColor: color.value } : {})"
                                    @click="handleColorSelect(color.value)"
                                    :title="color.name"
                                >
                                    <!-- Custom color button content -->
                                    <template v-if="color.name === 'Custom'">
                                        <div 
                                            class="absolute inset-0 flex items-center justify-center custom-color-trigger"
                                            @click.stop="handleColorSelect('')"
                                        >
                                            <!-- Outer dashed circle -->
                                            <div 
                                                class="absolute -inset-1 border-2 border-dashed rounded-full transition-all duration-300"
                                                :class="[
                                                    isCustomColor 
                                                        ? 'border-gray-400/30' 
                                                        : 'border-gray-400 opacity-50'
                                                ]"
                                            ></div>
                                            <!-- Inner circle with plus sign or selected color -->
                                            <div 
                                                class="w-5 h-5 relative rounded-full shadow-sm transition-all duration-300 group-hover:scale-110"
                                                :style="{ backgroundColor: isCustomColor ? 'transparent' : 'transparent' }"
                                            >
                                                <!-- Plus sign (only show if no custom color is selected) -->
                                                <template v-if="!isCustomColor">
                                                    <div class="absolute inset-0 flex items-center justify-center">
                                                        <div class="w-3 h-0.5 bg-gray-400 transition-colors duration-200 group-hover:bg-gray-600"></div>
                                                    </div>
                                                    <div class="absolute inset-0 flex items-center justify-center">
                                                        <div class="h-3 w-0.5 bg-gray-400 transition-colors duration-200 group-hover:bg-gray-600"></div>
                                                    </div>
                                                </template>
                                                <!-- Checkmark for selected custom color -->
                                                <template v-else>
                                                    <div class="absolute inset-0 flex items-center justify-center">
                                                        <svg 
                                                            class="w-3 h-3 text-white transition-opacity duration-300"
                                                            :class="{ 'opacity-0': !isCustomColor, 'opacity-100': isCustomColor }"
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
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                    <!-- Color tooltip -->
                                    <div 
                                        class="absolute -top-8 left-1/2 transform -translate-x-1/2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap"
                                    >
                                        {{ color.name }}
                                    </div>
                                </button>
                            </div>
                            
                            <!-- Enhanced custom color input with smoother transition -->
                            <Transition
                                enter-active-class="transition-all duration-300 ease-out"
                                enter-from-class="opacity-0 -translate-y-4 scale-95"
                                enter-to-class="opacity-100 translate-y-0 scale-100"
                                leave-active-class="transition-all duration-250 ease-in"
                                leave-from-class="opacity-100 translate-y-0 scale-100"
                                leave-to-class="opacity-0 -translate-y-4 scale-95"
                            >
                                <div 
                                    v-if="showCustomColorInput" 
                                    class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200 origin-top custom-color-panel"
                                >
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1">
                                            <Label class="text-sm text-gray-600 mb-1.5 block">Custom Color</Label>
                                            <div class="flex items-center gap-2">
                                                <input
                                                    type="color"
                                                    v-model="customColor"
                                                    @input="handleCustomColorChange"
                                                    @click.stop
                                                    class="w-12 h-12 rounded-lg cursor-pointer border border-gray-200 p-1 bg-white"
                                                />
                                                <input
                                                    type="text"
                                                    v-model="customColor"
                                                    @input="handleCustomColorChange"
                                                    @click.stop
                                                    class="flex-1 px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
                                                    placeholder="#000000"
                                                />
                                            </div>
                                        </div>
                                        <div 
                                            class="w-12 h-12 rounded-lg border border-gray-200"
                                            :style="{ backgroundColor: customColor }"
                                        ></div>
                                    </div>
                                </div>
                            </Transition>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button
                            type="button"
                            variant="outline"
                            @click="showRoleModal = false"
                        >
                            Cancel
                        </Button>
                        <Button 
                            type="submit"
                            :disabled="!roleForm.name || !roleForm.description"
                        >
                            {{ isEditingRole ? 'Save Changes' : 'Add Role' }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Role Delete Confirmation Modal -->
        <Dialog v-model:open="showRoleDeleteModal">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Delete Role</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete the role "{{ roleToDelete?.name }}"? This action cannot be undone.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="mt-6">
                    <Button 
                        variant="outline" 
                        @click="showRoleDeleteModal = false"
                    >
                        Cancel
                    </Button>
                    <Button 
                        variant="destructive" 
                        @click="deleteRole"
                    >
                        Delete
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Attendance Status Form Modal -->
        <Dialog v-model:open="showAttendanceStatusModal">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{{ isEditingAttendanceStatus ? 'Edit Attendance Status' : 'Add Attendance Status' }}</DialogTitle>
                    <DialogDescription>
                        {{ isEditingAttendanceStatus ? 'Update the attendance status details below.' : 'Fill in the details below to add a new attendance status.' }}
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="saveAttendanceStatus" class="grid gap-4 py-4 pb-0">
                    <div class="grid gap-2">
                        <Label for="statusName">Status Name</Label>
                        <Input
                            id="statusName"
                            v-model="attendanceStatusForm.name"
                            placeholder="Enter status name"
                            required
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="statusDescription">Description</Label>
                        <Textarea
                            id="statusDescription"
                            v-model="attendanceStatusForm.description"
                            placeholder="Enter status description"
                            required
                            :rows="3"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="statusStatus">Status</Label>
                        <select
                            id="statusStatus"
                            v-model="attendanceStatusForm.status"
                            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
                        >
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="grid gap-2">
                        <Label for="statusColor">Status Color</Label>
                        <div class="flex flex-col gap-3">
                            <div class="flex flex-wrap gap-2">
                                <button
                                    v-for="color in roleColors"
                                    :key="color.value"
                                    type="button"
                                    class="relative w-9 h-9 rounded-full border-2 transition-all duration-200 group"
                                    :class="[
                                        attendanceStatusForm.color === color.value ? 'border-primary scale-110 ring-2 ring-primary/20' : 'border-gray-200 hover:border-gray-300',
                                        color.name === 'Custom' ? (isAttendanceCustomColor ? '' : 'bg-gradient-to-br from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200') : ''
                                    ]"
                                    :style="color.name === 'Custom' && isAttendanceCustomColor ? { backgroundColor: attendanceStatusForm.color } : (color.name !== 'Custom' ? { backgroundColor: color.value } : {})"
                                    @click="handleAttendanceColorSelect(color.value)"
                                    :title="color.name"
                                >
                                    <!-- Custom color button content -->
                                    <template v-if="color.name === 'Custom'">
                                        <div 
                                            class="absolute inset-0 flex items-center justify-center custom-color-trigger"
                                            @click.stop="handleAttendanceColorSelect('')"
                                        >
                                            <!-- Outer dashed circle -->
                                            <div 
                                                class="absolute -inset-1 border-2 border-dashed rounded-full transition-all duration-300"
                                                :class="[
                                                    isAttendanceCustomColor 
                                                        ? 'border-gray-400/30' 
                                                        : 'border-gray-400 opacity-50'
                                                ]"
                                            ></div>
                                            <!-- Inner circle with plus sign or selected color -->
                                            <div 
                                                class="w-5 h-5 relative rounded-full shadow-sm transition-all duration-300 group-hover:scale-110"
                                                :style="{ backgroundColor: isAttendanceCustomColor ? 'transparent' : 'transparent' }"
                                            >
                                                <!-- Plus sign (only show if no custom color is selected) -->
                                                <template v-if="!isAttendanceCustomColor">
                                                    <div class="absolute inset-0 flex items-center justify-center">
                                                        <div class="w-3 h-0.5 bg-gray-400 transition-colors duration-200 group-hover:bg-gray-600"></div>
                                                    </div>
                                                    <div class="absolute inset-0 flex items-center justify-center">
                                                        <div class="h-3 w-0.5 bg-gray-400 transition-colors duration-200 group-hover:bg-gray-600"></div>
                                                    </div>
                                                </template>
                                                <!-- Checkmark for selected custom color -->
                                                <template v-else>
                                                    <div class="absolute inset-0 flex items-center justify-center">
                                                        <svg 
                                                            class="w-3 h-3 text-white transition-opacity duration-300"
                                                            :class="{ 'opacity-0': !isAttendanceCustomColor, 'opacity-100': isAttendanceCustomColor }"
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
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                    <!-- Color tooltip -->
                                    <div 
                                        class="absolute -top-8 left-1/2 transform -translate-x-1/2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap"
                                    >
                                        {{ color.name }}
                                    </div>
                                </button>
                            </div>
                            
                            <!-- Enhanced custom color input with smoother transition -->
                            <Transition
                                enter-active-class="transition-all duration-300 ease-out"
                                enter-from-class="opacity-0 -translate-y-4 scale-95"
                                enter-to-class="opacity-100 translate-y-0 scale-100"
                                leave-active-class="transition-all duration-250 ease-in"
                                leave-from-class="opacity-100 translate-y-0 scale-100"
                                leave-to-class="opacity-0 -translate-y-4 scale-95"
                            >
                                <div 
                                    v-if="showAttendanceCustomColorInput" 
                                    class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200 origin-top custom-color-panel"
                                >
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1">
                                            <Label class="text-sm text-gray-600 mb-1.5 block">Custom Color</Label>
                                            <div class="flex items-center gap-2">
                                                <input
                                                    type="color"
                                                    v-model="attendanceCustomColor"
                                                    @input="handleAttendanceCustomColorChange"
                                                    @click.stop
                                                    class="w-12 h-12 rounded-lg cursor-pointer border border-gray-200 p-1 bg-white"
                                                />
                                                <input
                                                    type="text"
                                                    v-model="attendanceCustomColor"
                                                    @input="handleAttendanceCustomColorChange"
                                                    @click.stop
                                                    class="flex-1 px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
                                                    placeholder="#000000"
                                                />
                                            </div>
                                        </div>
                                        <div 
                                            class="w-12 h-12 rounded-lg border border-gray-200"
                                            :style="{ backgroundColor: attendanceCustomColor }"
                                        ></div>
                                    </div>
                                </div>
                            </Transition>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button
                            type="button"
                            variant="outline"
                            @click="showAttendanceStatusModal = false"
                        >
                            Cancel
                        </Button>
                        <Button 
                            type="submit"
                            :disabled="!attendanceStatusForm.name || !attendanceStatusForm.description"
                        >
                            {{ isEditingAttendanceStatus ? 'Save Changes' : 'Add Status' }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Attendance Status Delete Confirmation Modal -->
        <Dialog v-model:open="showAttendanceStatusDeleteModal">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Delete Attendance Status</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete the attendance status "{{ attendanceStatusToDelete?.name }}"? This action cannot be undone.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="mt-6">
                    <Button 
                        variant="outline" 
                        @click="showAttendanceStatusDeleteModal = false"
                    >
                        Cancel
                    </Button>
                    <Button 
                        variant="destructive" 
                        @click="deleteAttendanceStatus"
                    >
                        Delete
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Unified Action Menu -->
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <div 
                v-if="activeMenu.type !== null && activeMenu.id !== null"
                class="fixed z-[100] origin-top-right"
                :style="{
                    left: `${menuPosition.x}px`,
                    top: `${menuPosition.y}px`
                }"
            >
                <div class="w-48 bg-white rounded-lg shadow-lg border py-1">
                    <!-- Role Menu Actions -->
                    <template v-if="activeMenu.type === 'role'">
                    <button 
                            @click.stop="updateRoleStatus(roles.find(r => r.id === activeMenu.id)!, 'active'); activeMenu = { type: null, id: null }"
                        class="w-full px-4 py-2 text-sm text-left hover:bg-gray-100 flex items-center gap-2 transition-colors duration-150"
                    >
                        <CheckCircle class="w-4 h-4 text-green-600" />
                        Set Active
                    </button>
                    <button 
                            @click.stop="updateRoleStatus(roles.find(r => r.id === activeMenu.id)!, 'inactive'); activeMenu = { type: null, id: null }"
                        class="w-full px-4 py-2 text-sm text-left hover:bg-gray-100 flex items-center gap-2 transition-colors duration-150"
                    >
                        <XCircle class="w-4 h-4 text-gray-600" />
                        Set Inactive
                    </button>
                    <div class="border-t my-1"></div>
                    <button 
                            @click.stop="confirmDeleteRole(roles.find(r => r.id === activeMenu.id)!); activeMenu = { type: null, id: null }"
                        class="w-full px-4 py-2 text-sm text-left hover:bg-gray-100 text-red-600 flex items-center gap-2 transition-colors duration-150"
                    >
                        <Trash2 class="w-4 h-4" />
                        Delete
                    </button>
                    </template>

                    <!-- Attendance Status Menu Actions -->
                    <template v-if="activeMenu.type === 'attendance'">
                    <button 
                            @click.stop="updateAttendanceStatus(attendanceStatuses.find(s => s.id === activeMenu.id)!, 'active'); activeMenu = { type: null, id: null }"
                        class="w-full px-4 py-2 text-sm text-left hover:bg-gray-100 flex items-center gap-2 transition-colors duration-150"
                    >
                        <CheckCircle class="w-4 h-4 text-green-600" />
                        Set Active
                    </button>
                    <button 
                            @click.stop="updateAttendanceStatus(attendanceStatuses.find(s => s.id === activeMenu.id)!, 'inactive'); activeMenu = { type: null, id: null }"
                        class="w-full px-4 py-2 text-sm text-left hover:bg-gray-100 flex items-center gap-2 transition-colors duration-150"
                    >
                        <XCircle class="w-4 h-4 text-gray-600" />
                        Set Inactive
                    </button>
                    <div class="border-t my-1"></div>
                    <button 
                            @click.stop="confirmDeleteAttendanceStatus(attendanceStatuses.find(s => s.id === activeMenu.id)!); activeMenu = { type: null, id: null }"
                        class="w-full px-4 py-2 text-sm text-left hover:bg-gray-100 text-red-600 flex items-center gap-2 transition-colors duration-150"
                    >
                        <Trash2 class="w-4 h-4" />
                        Delete
                    </button>
                    </template>
                </div>
            </div>
        </Transition>
    </AppLayout>
</template> 

<style>
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Enhanced transitions */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Smoother transitions for select and its options */
select {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: center;
    will-change: transform, opacity, border-color;
}

select option {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: center;
}

/* Enhanced hover and focus effects */
select:hover {
    border-color: var(--primary);
    transform: translateY(-1px);
}

select:focus {
    box-shadow: 0 0 0 2px var(--primary-20);
    transform: translateY(-1px);
}

/* Smoother highlight effect transition */
.bg-primary\/5 {
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: center;
    will-change: transform, opacity;
}

/* Add smooth transitions for cards */
.card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: center;
    will-change: transform, opacity, box-shadow;
}

/* Add a subtle scale effect on hover for interactive elements */
button, 
input[type="checkbox"],
.action-menu-trigger {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: center;
}

button:hover,
.action-menu-trigger:hover {
    transform: scale(1.05);
}

/* Smooth transition for table rows */
tr {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Add a subtle transition for pagination buttons */
.pagination-button {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: center;
}

.pagination-button:hover:not(:disabled) {
    transform: translateY(-1px);
}

/* Add smooth transitions for status badges */
.status-badge {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Add smooth transitions for action buttons */
.action-button {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: center;
}

.action-button:hover {
    transform: scale(1.1);
}
</style> 