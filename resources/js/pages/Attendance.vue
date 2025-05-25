<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ClipboardList, MoreVertical, Search, CalendarDays, ArrowRight } from 'lucide-vue-next';
import Card from '@/components/ui/card/Card.vue';
import Button from '@/components/ui/button/Button.vue';
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const breadcrumbs = [
    { title: 'Attendance', href: '/attendance' },
];

interface Employee {
    id: number;
    name: string;
    department: string;
    status: 'Present' | 'Late' | 'On Leave' | 'Absent';
}

interface EmployeeOnLeave {
    id: number;
    name: string;
    leaveType: string;
    endDate: string;
}

const employees = ref<Employee[]>([
    {
        id: 1,
        name: 'John Smith',
        department: 'Engineering',
        status: 'Present'
    },
    {
        id: 2,
        name: 'Sarah Johnson',
        department: 'Product',
        status: 'Late'
    },
    {
        id: 3,
        name: 'Michael Brown',
        department: 'Engineering',
        status: 'On Leave'
    },
    {
        id: 4,
        name: 'Emily Davis',
        department: 'Human Resources',
        status: 'Absent'
    }
]);

const employeesOnLeave = ref<EmployeeOnLeave[]>([
    {
        id: 1,
        name: 'Michael Brown',
        leaveType: 'Vacation',
        endDate: '2025-05-25'
    },
    {
        id: 2,
        name: 'David Wilson',
        leaveType: 'Sick',
        endDate: '2025-05-22'
    },
    {
        id: 3,
        name: 'Lisa Anderson',
        leaveType: 'Personal',
        endDate: '2025-05-30'
    }
]);

const search = ref('');
const currentPage = ref(1);
const pageSize = ref(10);
const pageSizeOptions = [10, 20, 50, 100];

// Filter states
const selectedDepartment = ref('');
const selectedStatus = ref('');

// Get unique values for filters
const departments = computed(() => Array.from(new Set(employees.value.map(e => e.department))));
const statuses = ['Present', 'Late', 'On Leave', 'Absent'];

const filteredEmployees = computed(() => {
    return employees.value.filter(employee => {
        // Search filter
        const searchMatch = 
            employee.name.toLowerCase().includes(search.value.toLowerCase()) ||
            employee.department.toLowerCase().includes(search.value.toLowerCase());

        // Department filter
        const departmentMatch = !selectedDepartment.value || 
            employee.department === selectedDepartment.value;

        // Status filter
        const statusMatch = !selectedStatus.value || 
            employee.status === selectedStatus.value;

        return searchMatch && departmentMatch && statusMatch;
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
    currentPage.value = 1;
}

function getDaysLeft(endDate: string): number {
    const today = new Date('2025-05-20'); // Set current date to May 20, 2025
    const end = new Date(endDate);
    const diffTime = end.getTime() - today.getTime();
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
}

function getBadgeColor(daysLeft: number): string {
    if (daysLeft <= 1) {
        return 'bg-red-100 text-red-800';
    } else if (daysLeft <= 4) {
        return 'bg-yellow-100 text-yellow-800';
    } else {
        return 'bg-green-100 text-green-800';
    }
}

function formatDaysLeft(days: number): string {
    return days === 1 ? '1 Day Left' : `${days} Days Left`;
}
</script>

<template>
    <Head title="Attendance" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <ClipboardList class="w-6 h-6" /> Attendance Management
                </h1>
            </div>
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Main Table -->
                <Card class="p-4 flex-1 rounded-lg shadow bg-white w-full">
                    <div class="flex flex-col md:flex-row md:items-center gap-3 mb-4">
                        <h2 class="text-lg font-semibold flex-1 mb-2 md:mb-0">Employee Attendance</h2>
                        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                            <div class="relative flex-1 min-w-[180px]">
                                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
                                <input 
                                    v-model="search" 
                                    type="text" 
                                    placeholder="Search employees..." 
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
                                v-model="selectedStatus" 
                                class="min-w-[180px] px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary h-[44px]"
                            >
                                <option value="">All Status</option>
                                <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-2 px-4 text-sm font-medium">Name</th>
                                    <th class="text-left py-2 px-4 text-sm font-medium">Department</th>
                                    <th class="text-left py-2 px-4 text-sm font-medium">Status</th>
                                    <th class="text-center py-2 px-4 text-sm font-medium">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="employee in paginatedEmployees" :key="employee.id" class="border-b hover:bg-gray-50">
                                    <td class="py-2 px-4 text-sm">{{ employee.name }}</td>
                                    <td class="py-2 px-4 text-sm">{{ employee.department }}</td>
                                    <td class="py-2 px-4">
                                        <span 
                                            class="inline-block px-2 py-0.5 rounded-full text-xs"
                                            :class="{
                                                'bg-green-100 text-green-800': employee.status === 'Present',
                                                'bg-yellow-100 text-yellow-800': employee.status === 'Late',
                                                'bg-blue-100 text-blue-800': employee.status === 'On Leave',
                                                'bg-red-100 text-red-800': employee.status === 'Absent'
                                            }"
                                        >
                                            {{ employee.status }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 text-center">
                                        <button class="p-1.5 hover:bg-gray-100 rounded">
                                            <MoreVertical class="w-4 h-4 text-gray-500" />
                                        </button>
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

                <!-- Employees on Leave -->
                <Card class="p-4 w-full md:w-80 mt-6 md:mt-0">
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="text-lg font-semibold">Employees on Leave</h2>
                        <Button 
                            variant="outline" 
                            size="sm" 
                            class="px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
                            as-child
                        >
                            <Link :href="route('leaves')" class="flex items-center gap-1">
                                View All
                                <ArrowRight class="w-4 h-4" />
                            </Link>
                        </Button>
                    </div>
                    <div class="border-b mb-1"></div>
                    <div class="space-y-4">
                        <div v-for="employee in employeesOnLeave" :key="employee.id" class="flex items-center justify-between">
                            <div>
                                <div class="font-medium">{{ employee.name }}</div>
                                <div class="text-sm text-gray-500">{{ employee.leaveType }}</div>
                            </div>
                            <div 
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium"
                                :class="getBadgeColor(getDaysLeft(employee.endDate))"
                            >
                                <CalendarDays class="w-3.5 h-3.5" />
                                {{ formatDaysLeft(getDaysLeft(employee.endDate)) }}
                            </div>
                        </div>
                    </div>
                </Card>
            </div>
        </div>
    </AppLayout>
</template> 