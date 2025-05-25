<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { CalendarDays, Mail, Phone, Check, X, Search, Eye, Clock, Calendar, Building, FileText } from 'lucide-vue-next';
import Card from '@/components/ui/card/Card.vue';
import Button from '@/components/ui/button/Button.vue';
import { ref, computed } from 'vue';

const breadcrumbs = [
    { title: 'Leaves', href: '/leaves' },
];

interface LeaveRequest {
    id: number;
    name: string;
    roles: string[];
    department: string;
    email: string;
    phone: string;
    leaveType: string;
    startDate: string;
    endDate: string;
    status: 'Pending' | 'Approved' | 'Rejected';
    reason: string;
    duration: string;
    remainingLeaves: {
        vacation: number;
        sick: number;
        personal: number;
    };
    attachments?: string[];
    notes?: string;
}

const leaveRequests = ref<LeaveRequest[]>([
    {
        id: 1,
        name: 'John Smith',
        roles: ['Developer', 'Team Lead'],
        department: 'Engineering',
        email: 'john.smith@company.com',
        phone: '+639 092 140 862',
        leaveType: 'Vacation',
        startDate: '2024-03-20',
        endDate: '2024-03-25',
        status: 'Pending',
        reason: 'Family vacation to Boracay',
        duration: '5 days',
        remainingLeaves: {
            vacation: 10,
            sick: 7,
            personal: 3
        },
        attachments: ['flight_tickets.pdf', 'hotel_reservation.pdf'],
        notes: 'Will be available for urgent matters via email'
    },
    {
        id: 2,
        name: 'Sarah Johnson',
        roles: ['Product Manager'],
        department: 'Product',
        email: 'sarah.j@company.com',
        phone: '+639 123 456 789',
        leaveType: 'Sick',
        startDate: '2024-03-18',
        endDate: '2024-03-19',
        status: 'Pending',
        reason: 'Medical check-up and recovery',
        duration: '2 days',
        remainingLeaves: {
            vacation: 15,
            sick: 5,
            personal: 2
        },
        attachments: ['medical_certificate.pdf'],
        notes: 'Regular check-up scheduled'
    },
    {
        id: 3,
        name: 'Michael Brown',
        roles: ['Developer'],
        department: 'Engineering',
        email: 'michael.b@company.com',
        phone: '+639 234 567 890',
        leaveType: 'Personal',
        startDate: '2024-03-22',
        endDate: '2024-03-23',
        status: 'Pending',
        reason: 'Personal matters',
        duration: '2 days',
        remainingLeaves: {
            vacation: 12,
            sick: 8,
            personal: 1
        },
        notes: 'Will be back on Monday'
    },
    {
        id: 4,
        name: 'Emily Davis',
        roles: ['HR Manager'],
        department: 'Human Resources',
        email: 'emily.d@company.com',
        phone: '+639 345 678 901',
        leaveType: 'Vacation',
        startDate: '2024-03-25',
        endDate: '2024-03-29',
        status: 'Pending',
        reason: 'Annual family vacation',
        duration: '5 days',
        remainingLeaves: {
            vacation: 8,
            sick: 10,
            personal: 3
        },
        attachments: ['travel_itinerary.pdf'],
        notes: 'Will be checking emails periodically'
    }
]);

const search = ref('');
const currentPage = ref(1);
const pageSize = ref(10);
const pageSizeOptions = [10, 20, 50, 100];

// Filter states
const selectedDepartment = ref('');
const selectedStatus = ref('');
const selectedLeaveType = ref('');

// Get unique values for filters
const departments = computed(() => Array.from(new Set(leaveRequests.value.map(e => e.department))));
const leaveTypes = computed(() => Array.from(new Set(leaveRequests.value.map(e => e.leaveType))));
const statuses = ['Pending', 'Approved', 'Rejected'];

const filteredRequests = computed(() => {
    return leaveRequests.value.filter(request => {
        // Search filter
        const searchMatch = 
            request.name.toLowerCase().includes(search.value.toLowerCase()) ||
            request.department.toLowerCase().includes(search.value.toLowerCase());

        // Department filter
        const departmentMatch = !selectedDepartment.value || 
            request.department === selectedDepartment.value;

        // Status filter
        const statusMatch = !selectedStatus.value || 
            request.status === selectedStatus.value;

        // Leave Type filter
        const leaveTypeMatch = !selectedLeaveType.value || 
            request.leaveType === selectedLeaveType.value;

        return searchMatch && departmentMatch && statusMatch && leaveTypeMatch;
    });
});

const paginatedRequests = computed(() => {
    const start = (currentPage.value - 1) * pageSize.value;
    const end = start + pageSize.value;
    return filteredRequests.value.slice(start, end);
});

const totalPages = computed(() => Math.ceil(filteredRequests.value.length / pageSize.value));

function changePage(page: number) {
    currentPage.value = page;
}

function changePageSize(size: number) {
    pageSize.value = size;
    currentPage.value = 1;
}

const selected = ref<number[]>([]);

function toggleSelectAll() {
    if (selected.value.length === filteredRequests.value.length) {
        selected.value = [];
    } else {
        selected.value = filteredRequests.value.map(e => e.id);
    }
}

function approveRequest(request: LeaveRequest) {
    request.status = 'Approved';
}

function rejectRequest(request: LeaveRequest) {
    request.status = 'Rejected';
}

function formatDate(dateStr: string) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
}

function getLeaveTypeBadgeColor(type: string): string {
    switch (type.toLowerCase()) {
        case 'vacation':
            return 'bg-blue-100 text-blue-800';
        case 'sick':
            return 'bg-red-100 text-red-800';
        case 'personal':
            return 'bg-purple-100 text-purple-800';
        case 'emergency':
            return 'bg-orange-100 text-orange-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

// Add bulk action functions
function approveSelectedRequests() {
    leaveRequests.value = leaveRequests.value.map(request => {
        if (selected.value.includes(request.id)) {
            return { ...request, status: 'Approved' };
        }
        return request;
    });
    selected.value = [];
}

function rejectSelectedRequests() {
    leaveRequests.value = leaveRequests.value.map(request => {
        if (selected.value.includes(request.id)) {
            return { ...request, status: 'Rejected' };
        }
        return request;
    });
    selected.value = [];
}

const selectedRequest = ref<LeaveRequest | null>(null);
const showDetailsModal = ref(false);

function viewRequestDetails(request: LeaveRequest) {
    selectedRequest.value = request;
    showDetailsModal.value = true;
}

function closeDetailsModal() {
    showDetailsModal.value = false;
    selectedRequest.value = null;
}
</script>

<template>
    <Head title="Leaves" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <CalendarDays class="w-6 h-6" /> Leave Management
                </h1>
            </div>
            <Card class="p-4">
                <div class="flex flex-col md:flex-row md:items-center gap-3 mb-4">
                    <h2 class="text-lg font-semibold flex-1 mb-2 md:mb-0">Leave Requests</h2>
                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        <div class="relative flex-1 min-w-[180px]">
                            <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
                            <input 
                                v-model="search" 
                                type="text" 
                                placeholder="Search requests..." 
                                class="w-full pl-10 pr-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary h-[44px]"
                            />
                        </div>
                        <select 
                            v-model="selectedDepartment" 
                            class="min-w-[180px] px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary h-[44px]"
                        >
                            <option value="">All Departments</option>
                            <option v-for="dept in departments" :key="dept" :value="dept">{{ dept }}</option>
                        </select>
                        <select 
                            v-model="selectedLeaveType" 
                            class="min-w-[180px] px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary h-[44px]"
                        >
                            <option value="">All Leave Types</option>
                            <option v-for="type in leaveTypes" :key="type" :value="type">{{ type }}</option>
                        </select>
                        <select 
                            v-model="selectedStatus" 
                            class="min-w-[180px] px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary h-[44px]"
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
                    <div v-if="selected.length > 0" class="mb-4 p-3 bg-gray-50 rounded-lg flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                        <span class="text-sm text-gray-600">{{ selected.length }} selected</span>
                        <div class="flex gap-2 flex-wrap">
                            <Button 
                                size="sm" 
                                variant="outline"
                                @click="approveSelectedRequests"
                                class="transition-colors duration-200 text-green-600 hover:text-green-700 hover:bg-green-50"
                            >
                                <Check class="w-4 h-4 mr-1" />
                                Approve Selected
                            </Button>
                            <Button 
                                size="sm" 
                                variant="outline"
                                @click="rejectSelectedRequests"
                                class="transition-colors duration-200 text-red-600 hover:text-red-700 hover:bg-red-50"
                            >
                                <X class="w-4 h-4 mr-1" />
                                Reject Selected
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
                                            :checked="selected.length === filteredRequests.length"
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
                                <th class="text-left py-2 px-4 text-sm font-medium">Department</th>
                                <th class="text-left py-2 px-4 text-sm font-medium">Contact</th>
                                <th class="text-left py-2 px-4 text-sm font-medium">Leave Type</th>
                                <th class="text-left py-2 px-4 text-sm font-medium">Start Date</th>
                                <th class="text-left py-2 px-4 text-sm font-medium">End Date</th>
                                <th class="text-center py-2 px-4 text-sm font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr 
                                v-for="request in paginatedRequests" 
                                :key="request.id" 
                                class="border-b transition-colors duration-200 ease-in-out"
                                :class="[
                                    selected.includes(request.id) 
                                        ? 'bg-primary/5 hover:bg-primary/10' 
                                        : 'hover:bg-gray-50'
                                ]"
                            >
                                <td class="text-center py-2 px-4">
                                    <div class="relative inline-flex items-center">
                                        <input 
                                            type="checkbox" 
                                            :checked="selected.includes(request.id)"
                                            @change="selected.includes(request.id) ? selected = selected.filter(id => id !== request.id) : selected.push(request.id)"
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
                                <td class="py-2 px-4 text-sm">{{ request.name }}</td>
                                <td class="py-2 px-4 text-sm">{{ request.department }}</td>
                                <td class="py-2 px-4">
                                    <div class="flex flex-col gap-0.5">
                                        <div class="flex items-center gap-1 text-xs">
                                            <Mail class="w-3.5 h-3.5 text-gray-500" />
                                            <a :href="'mailto:' + request.email" class="text-blue-600 hover:underline">
                                                {{ request.email }}
                                            </a>
                                        </div>
                                        <div class="flex items-center gap-1 text-xs">
                                            <Phone class="w-3.5 h-3.5 text-gray-500" />
                                            <a :href="'tel:' + request.phone" class="text-blue-600 hover:underline">
                                                {{ request.phone }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2 px-4">
                                    <span 
                                        class="inline-block px-2 py-0.5 rounded-full text-xs font-medium"
                                        :class="getLeaveTypeBadgeColor(request.leaveType)"
                                    >
                                        {{ request.leaveType }}
                                    </span>
                                </td>
                                <td class="py-2 px-4 text-sm">{{ formatDate(request.startDate) }}</td>
                                <td class="py-2 px-4 text-sm">{{ formatDate(request.endDate) }}</td>
                                <td class="py-2 px-4">
                                    <div class="flex justify-center gap-2">
                                        <button 
                                            @click="viewRequestDetails(request)" 
                                            class="p-1.5 hover:bg-gray-100 rounded" 
                                            title="View Details"
                                        >
                                            <Eye class="w-4 h-4 text-gray-600" />
                                        </button>
                                        <template v-if="request.status === 'Pending'">
                                            <button 
                                                @click="approveRequest(request)" 
                                                class="p-1.5 hover:bg-green-100 rounded" 
                                                title="Approve"
                                            >
                                                <Check class="w-4 h-4 text-green-600" />
                                            </button>
                                            <button 
                                                @click="rejectRequest(request)" 
                                                class="p-1.5 hover:bg-red-100 rounded" 
                                                title="Reject"
                                            >
                                                <X class="w-4 h-4 text-red-600" />
                                            </button>
                                        </template>
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
                            class="border rounded px-2 py-1 text-sm h-[36px]"
                        >
                            <option v-for="size in pageSizeOptions" :key="size" :value="size">{{ size }}</option>
                        </select>
                        <span class="text-sm text-gray-500">entries</span>
                    </div>
                    <div class="text-xs text-gray-400 sm:text-sm sm:text-gray-500 text-left">
                        Showing {{ (currentPage - 1) * pageSize + 1 }} to {{ Math.min(currentPage * pageSize, filteredRequests.length) }} of {{ filteredRequests.length }} entries
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
    </AppLayout>

    <!-- Leave Request Details Modal -->
    <div v-if="showDetailsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30">
        <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-2xl relative">
            <button @click="closeDetailsModal" class="absolute top-3 right-3 p-2 rounded-full bg-gray-100 hover:bg-gray-200">
                <X class="w-5 h-5 text-gray-500" />
            </button>
            
            <div v-if="selectedRequest" class="space-y-6">
                <!-- Header -->
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ selectedRequest.name }}</h3>
                        <p class="text-sm text-gray-500">{{ selectedRequest.roles.join(', ') }}</p>
                    </div>
                    <span 
                        class="inline-block px-3 py-1 rounded-full text-sm font-medium"
                        :class="{
                            'bg-yellow-100 text-yellow-800': selectedRequest.status === 'Pending',
                            'bg-green-100 text-green-800': selectedRequest.status === 'Approved',
                            'bg-red-100 text-red-800': selectedRequest.status === 'Rejected'
                        }"
                    >
                        {{ selectedRequest.status }}
                    </span>
                </div>

                <!-- Leave Details -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <Building class="w-5 h-5 text-gray-400" />
                            <div>
                                <p class="text-sm text-gray-500">Department</p>
                                <p class="font-medium">{{ selectedRequest.department }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <Calendar class="w-5 h-5 text-gray-400" />
                            <div>
                                <p class="text-sm text-gray-500">Duration</p>
                                <p class="font-medium">{{ selectedRequest.duration }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <Clock class="w-5 h-5 text-gray-400" />
                            <div>
                                <p class="text-sm text-gray-500">Dates</p>
                                <p class="font-medium">{{ formatDate(selectedRequest.startDate) }} - {{ formatDate(selectedRequest.endDate) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <Mail class="w-5 h-5 text-gray-400" />
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <a :href="'mailto:' + selectedRequest.email" class="font-medium text-blue-600 hover:underline">
                                    {{ selectedRequest.email }}
                                </a>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <Phone class="w-5 h-5 text-gray-400" />
                            <div>
                                <p class="text-sm text-gray-500">Phone</p>
                                <a :href="'tel:' + selectedRequest.phone" class="font-medium text-blue-600 hover:underline">
                                    {{ selectedRequest.phone }}
                                </a>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <FileText class="w-5 h-5 text-gray-400" />
                            <div>
                                <p class="text-sm text-gray-500">Leave Type</p>
                                <span 
                                    class="inline-block px-2 py-0.5 rounded-full text-xs font-medium"
                                    :class="getLeaveTypeBadgeColor(selectedRequest.leaveType)"
                                >
                                    {{ selectedRequest.leaveType }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reason and Notes -->
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-1">Reason for Leave</h4>
                        <p class="text-gray-600">{{ selectedRequest.reason }}</p>
                    </div>
                    <div v-if="selectedRequest.notes">
                        <h4 class="text-sm font-medium text-gray-700 mb-1">Additional Notes</h4>
                        <p class="text-gray-600">{{ selectedRequest.notes }}</p>
                    </div>
                </div>

                <!-- Remaining Leaves -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Remaining Leaves</h4>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <p class="text-xs text-blue-600">Vacation</p>
                            <p class="text-lg font-bold text-blue-700">{{ selectedRequest.remainingLeaves.vacation }} days</p>
                        </div>
                        <div class="bg-red-50 p-3 rounded-lg">
                            <p class="text-xs text-red-600">Sick</p>
                            <p class="text-lg font-bold text-red-700">{{ selectedRequest.remainingLeaves.sick }} days</p>
                        </div>
                        <div class="bg-purple-50 p-3 rounded-lg">
                            <p class="text-xs text-purple-600">Personal</p>
                            <p class="text-lg font-bold text-purple-700">{{ selectedRequest.remainingLeaves.personal }} days</p>
                        </div>
                    </div>
                </div>

                <!-- Attachments -->
                <div v-if="selectedRequest.attachments && selectedRequest.attachments.length > 0">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Attachments</h4>
                    <div class="flex flex-wrap gap-2">
                        <div 
                            v-for="attachment in selectedRequest.attachments" 
                            :key="attachment"
                            class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 rounded-lg"
                        >
                            <FileText class="w-4 h-4 text-gray-400" />
                            <span class="text-sm text-gray-600">{{ attachment }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <Button 
                        variant="outline" 
                        @click="closeDetailsModal"
                    >
                        Close
                    </Button>
                    <template v-if="selectedRequest.status === 'Pending'">
                        <Button 
                            variant="outline"
                            class="text-green-600 hover:text-green-700 hover:bg-green-50"
                            @click="approveRequest(selectedRequest); closeDetailsModal()"
                        >
                            <Check class="w-4 h-4 mr-1" />
                            Approve
                        </Button>
                        <Button 
                            variant="outline"
                            class="text-red-600 hover:text-red-700 hover:bg-red-50"
                            @click="rejectRequest(selectedRequest); closeDetailsModal()"
                        >
                            <X class="w-4 h-4 mr-1" />
                            Reject
                        </Button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template> 