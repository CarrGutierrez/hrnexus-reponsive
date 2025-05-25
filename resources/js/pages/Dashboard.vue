<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Card from '@/components/ui/card/Card.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import Button from '@/components/ui/button/Button.vue';
import Tooltip from '@/components/ui/tooltip/Tooltip.vue';
import TooltipTrigger from '@/components/ui/tooltip/TooltipTrigger.vue';
import TooltipContent from '@/components/ui/tooltip/TooltipContent.vue';
import { Line, Pie } from 'vue-chartjs';
import {
  Chart as ChartJS,
  Title,
  Tooltip as ChartTooltip,
  Legend,
  LineElement,
  PointElement,
  CategoryScale,
  LinearScale,
  ArcElement
} from 'chart.js';
import { LayoutGrid } from 'lucide-vue-next';
import { ref, computed } from 'vue';

ChartJS.register(Title, ChartTooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale, ArcElement);

const totalEmployees = 52;
const attendanceSummary = {
  present: 44,
  late: 3,
  absent: 2,
  onLeave: 3,
};
const pendingLeaveRequests = 4;
const today = new Date();
const events = [
  { title: 'Weekly Team Meeting', date: '2025-05-20', time: '09:00', description: 'Discuss project updates.', type: 'meeting', location: 'Conference Room A', attendees: 5 },
  { title: 'Client Presentation', date: '2025-05-21', time: '13:00', description: 'Present Q2 progress.', type: 'meeting', location: 'Virtual Meeting', attendees: 8 },
  { title: 'HR Policy Review', date: '2025-05-22', time: '15:00', description: 'Review new HR policies.', type: 'event', location: 'Main Hall', attendees: 12 },
  { title: 'Product Demo', date: '2025-05-25', time: '11:00', description: 'Demo new features.', type: 'meeting', location: 'Demo Room', attendees: 6 },
  { title: 'Public Holiday', date: '2025-05-26', time: '00:00', description: 'Memorial Day', type: 'holiday', location: 'N/A', attendees: 0 },
  { title: 'Team Building', date: '2025-05-27', time: '10:00', description: 'Annual team building event.', type: 'event', location: 'Central Park', attendees: 25 },
  { title: 'Board Meeting', date: '2025-05-28', time: '14:00', description: 'Quarterly board meeting.', type: 'meeting', location: 'Board Room', attendees: 10 },
  { title: 'Training Session', date: '2025-05-29', time: '09:30', description: 'New software training.', type: 'event', location: 'Training Room', attendees: 15 },
  { title: 'National Holiday', date: '2025-05-30', time: '00:00', description: 'Independence Day', type: 'holiday', location: 'N/A', attendees: 0 },
  { title: 'Project Review', date: '2025-05-31', time: '11:00', description: 'Monthly project review.', type: 'meeting', location: 'Conference Room B', attendees: 7 },
];
const upcomingEvents = events.filter(e => {
  const d = new Date(e.date);
  return d >= today && d <= new Date(today.getFullYear(), today.getMonth(), today.getDate() + 3);
});

const monthlyAttendance = {
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
  datasets: [
    {
      label: 'Present',
      data: [40, 42, 45, 43, 44, 46],
      borderColor: '#2563eb',
      backgroundColor: 'rgba(37,99,235,0.1)',
      fill: true,
      tension: 0.4,
    },
    {
      label: 'Absent',
      data: [2, 1, 3, 2, 2, 1],
      borderColor: '#ef4444',
      backgroundColor: 'rgba(239,68,68,0.1)',
      fill: true,
      tension: 0.4,
    },
    {
      label: 'Late',
      data: [3, 2, 2, 3, 3, 2],
      borderColor: '#f59e42',
      backgroundColor: 'rgba(245,158,66,0.1)',
      fill: true,
      tension: 0.4,
    },
    {
      label: 'On Leave',
      data: [2, 3, 2, 2, 3, 2],
      borderColor: '#10b981',
      backgroundColor: 'rgba(16,185,129,0.1)',
      fill: true,
      tension: 0.4,
    },
  ],
};
const attendanceOptions = {
  responsive: true,
  plugins: {
    legend: { display: true, position: 'top' as const },
    tooltip: { enabled: true },
    title: { display: false },
  },
  scales: {
    y: { beginAtZero: true, title: { display: true, text: 'Count' } },
    x: { title: { display: true, text: 'Month' } },
  },
};
const leavePie = {
  labels: ['Vacation', 'Sick', 'Emergency', 'Maternity', 'Paternity'],
  datasets: [
    {
      label: 'Leave Type',
      data: [12, 5, 2, 1, 1],
      backgroundColor: ['#2563eb', '#ef4444', '#f59e42', '#10b981', '#a78bfa'],
    },
  ],
};
const leavePieOptions = {
  responsive: true,
  plugins: {
    legend: { display: true, position: 'bottom' as const },
    tooltip: { enabled: true },
    title: { display: false },
  },
};
const notifications = [
  { id: 1, type: 'system', message: 'System maintenance scheduled for May 25', timeAgo: '2h ago', icon: 'ℹ️', unread: false },
  { id: 2, type: 'overdue', message: 'Mark Lee has an overdue leave approval', timeAgo: '1d ago', icon: '⏰', unread: true },
  { id: 3, type: 'balance', message: 'Jane Doe has 1 day Vacation Leave remaining', timeAgo: '2d ago', icon: '⚠️', unread: false },
];

// Schedule card state
const currentMonth = ref(new Date());
const selectedDate = ref(new Date());
const activeTab = ref('meetings');

// Generate dates for the calendar strip
const calendarDates = computed(() => {
  const dates = [];
  const startDate = new Date(selectedDate.value);
  startDate.setDate(startDate.getDate() - 2); // Show 2 days before selected date

  for (let i = 0; i < 5; i++) {
    const date = new Date(startDate);
    date.setDate(date.getDate() + i);
    dates.push({
      date: date,
      day: date.getDate(),
      label: date.toLocaleDateString('en-US', { weekday: 'short' }),
      isSelected: date.toDateString() === selectedDate.value.toDateString()
    });
  }
  return dates;
});

// Filter events based on selected date and active tab
const filteredEvents = computed(() => {
  return events.filter(event => {
    const eventDate = new Date(event.date);
    const isSameDate = eventDate.toDateString() === selectedDate.value.toDateString();
    const matchesTab = activeTab.value === 'all' || event.type === activeTab.value;
    return isSameDate && matchesTab;
  });
});

function formatDate(dateStr: string): string {
  const date = new Date(dateStr);
  const monthAbbr = date.toLocaleString('en-US', { month: 'short' });
  const day = date.getDate();
  const year = date.getFullYear();
  return `${monthAbbr} ${day}, ${year}`;
}

function prevDay() {
  const newDate = new Date(selectedDate.value);
  newDate.setDate(newDate.getDate() - 1);
  selectedDate.value = newDate;
}

function nextDay() {
  const newDate = new Date(selectedDate.value);
  newDate.setDate(newDate.getDate() + 1);
  selectedDate.value = newDate;
}

function prevMonth() {
  const newDate = new Date(currentMonth.value);
  newDate.setMonth(newDate.getMonth() - 1);
  currentMonth.value = newDate;
}

function nextMonth() {
  const newDate = new Date(currentMonth.value);
  newDate.setMonth(newDate.getMonth() + 1);
  currentMonth.value = newDate;
}

function selectDate(date: Date) {
  selectedDate.value = date;
}

function setActiveTab(tab: string) {
  activeTab.value = tab;
}

// Dummy data for attendance employees
const attendanceEmployees = [
  {
    name: 'Floyd Miles',
    email: 'floydmiles@gmail.com',
    avatar: 'https://randomuser.me/api/portraits/men/1.jpg',
    status: 'On time',
    statusColor: 'green',
    timeIn: '08:53 AM',
    timeOut: '06:15 PM',
  },
  {
    name: 'Kristin Watson',
    email: 'kristinwats@gmail.com',
    avatar: 'https://randomuser.me/api/portraits/women/1.jpg',
    status: 'Late',
    statusColor: 'yellow',
    timeIn: '09:00 AM',
    timeOut: '05:00 PM',
  },
  {
    name: 'Wade Warren',
    email: 'wadewarren@gmail.com',
    avatar: 'https://randomuser.me/api/portraits/men/2.jpg',
    status: 'On time',
    statusColor: 'green',
    timeIn: '08:57 AM',
    timeOut: '05:03 PM',
  },
  {
    name: 'Jane Cooper',
    email: 'janecooper@gmail.com',
    avatar: 'https://randomuser.me/api/portraits/women/2.jpg',
    status: 'Absent',
    statusColor: 'gray',
    timeIn: '--',
    timeOut: '--',
  },
  {
    name: 'Robert Fox',
    email: 'robertfox@gmail.com',
    avatar: 'https://randomuser.me/api/portraits/men/3.jpg',
    status: 'On time',
    statusColor: 'green',
    timeIn: '08:45 AM',
    timeOut: '05:10 PM',
  },
  {
    name: 'Leslie Alexander',
    email: 'lesliealex@gmail.com',
    avatar: 'https://randomuser.me/api/portraits/women/3.jpg',
    status: 'Late',
    statusColor: 'yellow',
    timeIn: '09:10 AM',
    timeOut: '05:20 PM',
  },
  {
    name: 'Guy Hawkins',
    email: 'guyhawkins@gmail.com',
    avatar: 'https://randomuser.me/api/portraits/men/4.jpg',
    status: 'On time',
    statusColor: 'green',
    timeIn: '08:59 AM',
    timeOut: '05:00 PM',
  },
  {
    name: 'Jenny Wilson',
    email: 'jennywilson@gmail.com',
    avatar: 'https://randomuser.me/api/portraits/women/4.jpg',
    status: 'Absent',
    statusColor: 'gray',
    timeIn: '--',
    timeOut: '--',
  },
];

// Dummy leave requests and notifications for the combined card
const leaveRequests = [
  { id: 1, name: 'John Doe', type: 'Sick Leave', dates: 'May 20-22', status: 'Pending', date: 'May 19', avatar: 'https://randomuser.me/api/portraits/men/1.jpg' },
  { id: 2, name: 'Jane Smith', type: 'Vacation', dates: 'May 25-28', status: 'Approved', date: 'May 18', avatar: 'https://randomuser.me/api/portraits/women/2.jpg' },
  { id: 3, name: 'Mark Lee', type: 'Emergency', dates: 'May 17', status: 'Rejected', date: 'May 17', avatar: 'https://randomuser.me/api/portraits/men/3.jpg' },
  { id: 4, name: 'Anna Smith', type: 'Emergency', dates: 'May 16', status: 'Pending', date: 'May 16', avatar: 'https://randomuser.me/api/portraits/women/4.jpg' },
];
const leaveSummary = [
  { label: 'Pending', count: 2, color: 'yellow' },
  { label: 'Approved', count: 1, color: 'green' },
  { label: 'Rejected', count: 1, color: 'red' },
];

const searchQuery = ref('');
const statusFilter = ref('');
const selectedRequests = ref<number[]>([]);

const filteredRequests = computed(() => {
  return leaveRequests.filter(r => {
    const matchesStatus = !statusFilter.value || r.status === statusFilter.value;
    const matchesSearch = r.name.toLowerCase().includes(searchQuery.value.toLowerCase());
    return matchesStatus && matchesSearch;
  });
});

const allSelected = computed(() => filteredRequests.value.length > 0 && filteredRequests.value.every(r => selectedRequests.value.includes(r.id)));

function toggleAll() {
  if (allSelected.value) {
    selectedRequests.value = [];
  } else {
    selectedRequests.value = filteredRequests.value.map(r => r.id);
  }
}
function clearSelection() {
  selectedRequests.value = [];
}
function approveRequest(id: number) {
  // TODO: Implement approve logic (API call)
  const req = leaveRequests.find(r => r.id === id);
  if (req) req.status = 'Approved';
  clearSelection();
}
function rejectRequest(id: number) {
  // TODO: Implement reject logic (API call)
  const req = leaveRequests.find(r => r.id === id);
  if (req) req.status = 'Rejected';
  clearSelection();
}
function bulkApprove() {
  selectedRequests.value.forEach(id => approveRequest(id));
}
function bulkReject() {
  selectedRequests.value.forEach(id => rejectRequest(id));
}
function viewDetails(id: number) {
  // TODO: Show modal or navigate to details page
  alert('Show details for request ID: ' + id);
}
function exportCSV() {
  // Simple CSV export for demo
  const rows = [
    ['Employee', 'Type', 'Dates', 'Status'],
    ...filteredRequests.value.map(r => [r.name, r.type, r.dates, r.status])
  ];
  const csv = rows.map(row => row.join(',')).join('\n');
  const blob = new Blob([csv], { type: 'text/csv' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = 'leave_requests.csv';
  a.click();
  URL.revokeObjectURL(url);
}

</script>

<template>
  <Head title="Dashboard" />
  <AppLayout :breadcrumbs="[{ title: 'Dashboard', href: '/dashboard' }]">
    <div class="p-4 md:p-8 bg-gray-50 min-h-screen">
      <!-- Top Section: Employee Stats + Schedule -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8 items-start">
        <!-- Left: Employee Stats Cards -->
        <div class="flex flex-col gap-6 w-full lg:col-span-8 h-full">
          <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 w-full">
            <Card class="bg-white/60 backdrop-blur-md border border-white/30 shadow-lg rounded-2xl p-6 flex-1">
              <div class="flex items-center justify-between mb-1">
                <div class="flex items-center gap-3">
                  <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m13-7a4 4 0 11-8 0 4 4 0 018 0zM5 7a4 4 0 108 0 4 4 0 00-8 0z" />
                    </svg>
                  </div>
                  <span class="text-sm font-medium text-gray-600">Total Employees</span>
                  <div class="w-4 h-4 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                </div>
                <button class="text-gray-400 hover:text-gray-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                  </svg>
                </button>
              </div>
              <div class="flex items-center gap-2 mb-3">
                <span class="text-4xl font-bold text-gray-900">218</span>
                <span class="text-xs font-medium text-green-600 bg-green-50 px-1.5 py-0.5 rounded-full">+6.0%</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500">from Last Month</span>
                <button class="flex items-center gap-1 text-xs font-medium text-gray-600 hover:text-gray-900">
                  See Details
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
            </Card>

            <Card class="bg-white/60 backdrop-blur-md border border-white/30 shadow-lg rounded-2xl p-6 flex-1">
              <div class="flex items-center justify-between mb-1">
                <div class="flex items-center gap-3">
                  <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-2.21 1.79-4 4-4s4 1.79 4 4-1.79 4-4 4-4-1.79-4-4zm-8 8v-2a4 4 0 014-4h4a4 4 0 014 4v2" />
                    </svg>
                  </div>
                  <span class="text-sm font-medium text-gray-600">New Employees</span>
                  <div class="w-4 h-4 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                </div>
                <button class="text-gray-400 hover:text-gray-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                  </svg>
                </button>
              </div>
              <div class="flex items-center gap-2 mb-3">
                <span class="text-4xl font-bold text-gray-900">48</span>
                <span class="text-xs font-medium text-green-600 bg-green-50 px-1.5 py-0.5 rounded-full">+6.0%</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500">from Last Month</span>
                <button class="flex items-center gap-1 text-xs font-medium text-gray-600 hover:text-gray-900">
                  See Details
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
            </Card>

            <Card class="bg-white/60 backdrop-blur-md border border-white/30 shadow-lg rounded-2xl p-6 flex-1">
              <div class="flex items-center justify-between mb-1">
                <div class="flex items-center gap-3">
                  <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </div>
                  <span class="text-sm font-medium text-gray-600">Resigned Employees</span>
                  <div class="w-4 h-4 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                </div>
                <button class="text-gray-400 hover:text-gray-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                  </svg>
                </button>
              </div>
              <div class="flex items-center gap-2 mb-3">
                <span class="text-4xl font-bold text-gray-900">16</span>
                <span class="text-xs font-medium text-red-600 bg-red-50 px-1.5 py-0.5 rounded-full">-2.0%</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500">from Last Month</span>
                <button class="flex items-center gap-1 text-xs font-medium text-gray-600 hover:text-gray-900">
                  See Details
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
            </Card>
          </div>
          <!-- Today's Attendance Card (Moved up) -->
          <Card class="bg-white/60 backdrop-blur-md border border-white/30 shadow-lg rounded-2xl p-6 flex flex-col space-y-6">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="p-2 bg-purple-100 rounded-lg">
                  <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <span class="font-semibold text-gray-900 text-lg">Attendance</span>
              </div>
              <div class="flex items-center gap-1 bg-gray-100 rounded-lg p-1">
                <button class="px-4 py-1.5 text-sm font-medium rounded-md bg-white shadow text-gray-900">Day</button>
                <button class="px-4 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-900">Week</button>
                <button class="px-4 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-900">Month</button>
              </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
              <div class="flex items-center gap-3">
                <span class="text-4xl font-bold text-gray-900">92</span>
                <span class="text-sm text-gray-600">On time</span>
              </div>
              <div class="flex items-center gap-3">
                <span class="text-4xl font-bold text-gray-900">12</span>
                <span class="text-sm text-gray-600">Late</span>
              </div>
              <div class="flex items-center gap-3">
                <span class="text-4xl font-bold text-gray-900">5</span>
                <span class="text-sm text-gray-600">Absent</span>
              </div>
            </div>

            <!-- Employee List -->
            <div class="mt-2 max-h-[210px] overflow-y-auto pr-2">
              <div class="hidden md:grid grid-cols-4">
                <div class="px-4 py-2 bg-gray-50 rounded-tl-lg text-sm font-medium text-gray-500 flex items-center">Employee</div>
                <div class="px-4 py-2 bg-gray-50 text-sm font-medium text-gray-500 flex items-center justify-center">Status</div>
                <div class="px-4 py-2 bg-gray-50 text-sm font-medium text-gray-500 flex items-center justify-center">Time In</div>
                <div class="px-4 py-2 bg-gray-50 rounded-tr-lg text-sm font-medium text-gray-500 flex items-center justify-center">Time Out</div>
              </div>
              <div class="space-y-1">
                <div v-for="employee in attendanceEmployees" :key="employee.name"
                  class="md:grid md:grid-cols-4 px-0 py-3 hover:bg-gray-50 rounded-lg transition-colors items-center gap-y-2">
                  <!-- Mobile: stacked -->
                  <div class="flex flex-col md:hidden px-4 w-full">
                    <div class="flex items-center gap-3">
                      <img :src="employee.avatar" alt="Employee Avatar" class="w-8 h-8 rounded-full" />
                      <div class="flex flex-col">
                        <span class="text-sm font-medium text-gray-900">{{ employee.name }}</span>
                        <span class="text-xs text-gray-500">{{ employee.email }}</span>
                      </div>
                    </div>
                    <span class="mt-2 w-fit" :class="`px-3 py-1 text-xs font-medium text-${employee.statusColor}-700 bg-${employee.statusColor}-50 rounded-full`">{{ employee.status }}</span>
                    <span class="mt-2 text-sm text-gray-600">{{ employee.timeIn }}</span>
                    <span class="text-sm text-gray-600">{{ employee.timeOut }}</span>
                  </div>
                  <!-- Desktop: each column only its content -->
                  <div class="hidden md:flex items-center gap-3 px-4 w-full md:w-auto">
                    <img :src="employee.avatar" alt="Employee Avatar" class="w-8 h-8 rounded-full" />
                    <div class="flex flex-col">
                      <span class="text-sm font-medium text-gray-900">{{ employee.name }}</span>
                      <span class="text-xs text-gray-500">{{ employee.email }}</span>
                    </div>
                  </div>
                  <div class="hidden md:flex justify-center w-full md:w-auto">
                    <span :class="`px-3 py-1 text-xs font-medium text-${employee.statusColor}-700 bg-${employee.statusColor}-50 rounded-full`">{{ employee.status }}</span>
                  </div>
                  <div class="hidden md:flex justify-center w-full md:w-auto">
                    <span class="text-sm text-gray-600">{{ employee.timeIn }}</span>
                  </div>
                  <div class="hidden md:flex justify-center w-full md:w-auto">
                    <span class="text-sm text-gray-600">{{ employee.timeOut }}</span>
                  </div>
                </div>
              </div>
            </div>
          </Card>
        </div>
        <!-- Right: Schedule Card -->
        <Card class="bg-white/60 backdrop-blur-md border border-white/30 shadow-lg rounded-2xl p-0 flex flex-col h-full w-full max-w-xs mx-auto lg:col-span-4 self-stretch">
          <!-- Header -->
          <div class="flex items-center justify-between px-4 pt-4 pb-2">
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2" fill="none"/>
                <path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor" stroke-width="2"/>
              </svg>
              <span class="font-semibold text-gray-700">Schedule</span>
            </div>
            <Button variant="outline" size="sm" class="!px-3 !py-1 text-xs">See All</Button>
          </div>
          <!-- Month Navigation -->
          <div class="flex items-center justify-between px-4 mb-1">
            <span class="text-base font-semibold">{{ currentMonth.toLocaleDateString('en-US', { month: 'long', year: 'numeric' }) }}</span>
            <div class="flex gap-2">
              <button @click="prevMonth" class="text-gray-400 hover:text-gray-600 p-1">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
              </button>
              <button @click="nextMonth" class="text-gray-400 hover:text-gray-600 p-1">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
              </button>
            </div>
          </div>
          <!-- Calendar Strip -->
          <div class="flex items-center justify-center px-4 mb-2 w-full">
            <button @click="prevDay" class="text-gray-400 hover:text-gray-600 p-1 flex-none flex items-center justify-center">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
              </svg>
            </button>
            <div class="flex gap-1.5 justify-center items-center mx-auto max-w-xs w-full">
              <div v-for="date in calendarDates" :key="date.date.toISOString()"
                @click="selectDate(date.date)"
                :class="[
                  'flex flex-col items-center justify-center border cursor-pointer transition-all duration-200',
                  date.isSelected
                    ? 'bg-purple-600 text-white border-purple-600 shadow w-[48px] h-[48px] px-3 py-2 text-base font-bold scale-110 z-10 rounded-lg'
                    : 'bg-white text-gray-700 border-gray-200 hover:bg-purple-50 w-[36px] h-[36px] px-2 py-1 text-sm font-medium scale-100 z-0 rounded-lg'
                ]">
                <span>{{ date.day }}</span>
                <span class="text-[10px]">{{ date.label }}</span>
              </div>
            </div>
            <button @click="nextDay" class="text-gray-400 hover:text-gray-600 p-1 flex-none flex items-center justify-center">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
              </svg>
            </button>
          </div>
          <!-- Search Bar -->
          <div class="px-4 mb-2">
            <div class="relative">
              <input type="text" placeholder="Search..." class="w-full rounded-lg border border-gray-200 py-2 pl-10 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-purple-200" />
              <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2" fill="none"/>
                <path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="2"/>
              </svg>
            </div>
          </div>
          <!-- Tabs -->
          <div class="flex justify-center border-b border-gray-100 px-4 mb-1 gap-2">
            <button 
              @click="setActiveTab('meetings')"
              :class="[
                'flex items-center gap-1 text-xs font-semibold px-2 py-1 border-b-2 transition-colors',
                activeTab === 'meetings' ? 'border-purple-600 text-purple-700' : 'border-transparent text-gray-400 hover:text-gray-600'
              ]">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M16 3v4M8 3v4"/></svg>
              Meetings
            </button>
            <button 
              @click="setActiveTab('events')"
              :class="[
                'flex items-center gap-1 text-xs font-semibold px-2 py-1 border-b-2 transition-colors',
                activeTab === 'events' ? 'border-purple-600 text-purple-700' : 'border-transparent text-gray-400 hover:text-gray-600'
              ]">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
              Events
            </button>
            <button 
              @click="setActiveTab('holiday')"
              :class="[
                'flex items-center gap-1 text-xs font-semibold px-2 py-1 border-b-2 transition-colors',
                activeTab === 'holiday' ? 'border-purple-600 text-purple-700' : 'border-transparent text-gray-400 hover:text-gray-600'
              ]">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M16 3v4M8 3v4"/></svg>
              Holiday
            </button>
          </div>
          <!-- Event List -->
          <div class="flex-1 space-y-3 px-4 pb-4 overflow-y-auto">
            <div v-if="filteredEvents.length === 0" class="text-center py-8 text-gray-500 text-sm">
              No events scheduled for this date
            </div>
            <div v-for="(event, index) in filteredEvents.slice(0, 3)" :key="event.title" 
              :class="[
                'rounded-xl p-3 flex flex-col gap-2 border border-gray-100 hover:bg-gray-100 transition-colors cursor-pointer relative',
                event.type === 'meeting' ? 'bg-gradient-to-r from-orange-100 to-orange-50' : '',
                event.type === 'event' ? 'bg-gradient-to-r from-blue-100 to-blue-50' : '',
                event.type === 'holiday' ? 'bg-gradient-to-r from-green-100 to-green-50' : ''
              ]">
              <div class="flex items-center gap-2 mb-1">
                <span v-if="event.type === 'meeting'" class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-orange-200 text-orange-700">MARKETING</span>
                <span v-else-if="event.type === 'event'" class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-blue-200 text-blue-700">PRODUCT MANAGER</span>
                <span v-else-if="event.type === 'holiday'" class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-purple-200 text-purple-700">PARTNERSHIP</span>
                <button class="ml-auto text-gray-400 hover:text-gray-600">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
              </div>
              <span class="font-semibold text-sm text-gray-800">{{ event.title }}</span>
              <span class="text-xs text-gray-500">{{ event.time }} (UTC)</span>
              <span class="text-xs text-gray-400">On {{ event.location }}</span>
              <div class="flex items-center mt-1">
                <div class="flex -space-x-2">
                  <img v-for="n in Math.min(event.attendees, 3)" :key="n" 
                    class="w-6 h-6 rounded-full border-2 border-white bg-gray-200" 
                    :src="`https://randomuser.me/api/portraits/men/${n+10}.jpg`" 
                    alt="avatar" />
                </div>
                <span v-if="event.attendees > 3" class="ml-2 text-xs text-gray-500">+{{ event.attendees - 3 }}</span>
                <span class="ml-2 text-xs text-gray-500">On Google Meet</span>
              </div>
            </div>
            <div v-if="filteredEvents.length > 3" class="text-center pt-2">
              <button class="text-xs text-purple-600 hover:text-purple-800 font-medium">
                View {{ filteredEvents.length - 3 }} more events
              </button>
            </div>
          </div>
        </Card>
      </div>
      <!-- Monthly Attendance Trend Full Width -->
      <div class="mb-8">
        <Card class="bg-white/60 backdrop-blur-md border border-white/30 shadow-lg rounded-2xl p-4 md:p-6 flex flex-col space-y-4 h-full min-h-[260px] mt-0 w-full">
          <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-orange-100 rounded-lg">
                <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17v-2a4 4 0 014-4h10a4 4 0 014 4v2M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
              </div>
              <span class="font-semibold text-gray-700">Monthly Attendance Trend</span>
            </div>
            <button class="flex items-center gap-1 text-xs font-medium text-purple-600 hover:text-purple-800 bg-purple-50 px-2 py-1 rounded">
              View Details
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
          <!-- Summary Row -->
          <div class="grid grid-cols-4 gap-2 mb-2">
            <div class="flex flex-col items-center">
              <span class="flex items-center gap-1 text-xs font-semibold text-blue-700">
                <span class="w-2 h-2 rounded-full bg-blue-600 inline-block"></span> Present
              </span>
              <span class="text-lg font-bold text-gray-900">46</span>
              <span class="text-xs text-green-600 flex items-center gap-0.5">+4% <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7 7 7"/></svg></span>
            </div>
            <div class="flex flex-col items-center">
              <span class="flex items-center gap-1 text-xs font-semibold text-red-700">
                <span class="w-2 h-2 rounded-full bg-red-600 inline-block"></span> Absent
              </span>
              <span class="text-lg font-bold text-gray-900">1</span>
              <span class="text-xs text-green-600 flex items-center gap-0.5">-1% <svg class="w-3 h-3 rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7 7 7"/></svg></span>
            </div>
            <div class="flex flex-col items-center">
              <span class="flex items-center gap-1 text-xs font-semibold text-yellow-700">
                <span class="w-2 h-2 rounded-full bg-yellow-400 inline-block"></span> Late
              </span>
              <span class="text-lg font-bold text-gray-900">2</span>
              <span class="text-xs text-red-600 flex items-center gap-0.5">+1% <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7 7 7"/></svg></span>
            </div>
            <div class="flex flex-col items-center">
              <span class="flex items-center gap-1 text-xs font-semibold text-green-700">
                <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span> On Leave
              </span>
              <span class="text-lg font-bold text-gray-900">2</span>
              <span class="text-xs text-green-600 flex items-center gap-0.5">0% <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7 7 7"/></svg></span>
            </div>
          </div>
          <Line :data="monthlyAttendance" :options="{
            responsive: true,
            plugins: { legend: { display: false }, tooltip: { enabled: true }, title: { display: false } },
            scales: { y: { beginAtZero: true, title: { display: true, text: 'Count' } }, x: { title: { display: true, text: 'Month' } } }
          }" style="max-height:180px" />
          <!-- Legend -->
          <div class="flex justify-center gap-4 mt-2">
            <div class="flex items-center gap-1 text-xs"><span class="w-3 h-3 rounded-full bg-blue-600 inline-block"></span>Present</div>
            <div class="flex items-center gap-1 text-xs"><span class="w-3 h-3 rounded-full bg-red-600 inline-block"></span>Absent</div>
            <div class="flex items-center gap-1 text-xs"><span class="w-3 h-3 rounded-full bg-yellow-400 inline-block"></span>Late</div>
            <div class="flex items-center gap-1 text-xs"><span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span>On Leave</div>
          </div>
        </Card>
      </div>
      <!-- Leave Requests & Notifications Full Width -->
      <div class="mb-8">
        <Card class="bg-white/60 backdrop-blur-md border border-white/30 shadow-lg rounded-2xl p-6 flex flex-col gap-6 w-full">
          <!-- Header & Actions -->
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-purple-50 rounded-lg">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-gray-900">Leave Requests & Notifications</h3>
                <p class="text-sm text-gray-500">Manage and track leave requests</p>
              </div>
            </div>
            <div class="flex gap-2">
              <button @click="router.visit('/leave-requests')" class="inline-flex items-center px-3 py-2 text-sm font-medium text-purple-600 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                New Request
              </button>
              <button @click="router.visit('/notifications')" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                View All
              </button>
            </div>
          </div>

          <!-- Summary Row & Filters -->
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div class="flex flex-row gap-6">
              <div class="flex flex-col items-center">
                <span class="text-xs text-gray-500">Pending</span>
                <span class="text-lg font-bold text-yellow-600">{{ leaveRequests.filter(r => r.status === 'Pending').length }}</span>
              </div>
              <div class="flex flex-col items-center">
                <span class="text-xs text-gray-500">Approved</span>
                <span class="text-lg font-bold text-green-600">{{ leaveRequests.filter(r => r.status === 'Approved').length }}</span>
              </div>
              <div class="flex flex-col items-center">
                <span class="text-xs text-gray-500">Rejected</span>
                <span class="text-lg font-bold text-red-600">{{ leaveRequests.filter(r => r.status === 'Rejected').length }}</span>
              </div>
            </div>
            <div class="flex flex-row gap-2 items-center w-full sm:w-auto">
              <input v-model="searchQuery" type="text" placeholder="Search employee..." class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-200 w-full sm:w-auto min-w-[160px]" />
              <select v-model="statusFilter" class="border border-gray-200 rounded-lg px-3 py-2 text-sm w-full sm:w-auto min-w-[120px]">
                <option value="">All</option>
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
              </select>
              <button class="text-xs text-purple-600 hover:underline ml-auto sm:ml-2" @click="exportCSV">Export CSV</button>
            </div>
          </div>
          <!-- Bulk Actions -->
          <div v-if="selectedRequests.length > 0" class="flex items-center gap-2 mb-2">
            <span class="text-xs text-gray-600">Bulk actions for {{ selectedRequests.length }} selected:</span>
            <button class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200" @click="bulkApprove">Approve</button>
            <button class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200" @click="bulkReject">Reject</button>
            <button class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200" @click="clearSelection">Clear</button>
          </div>
          <!-- Requests Table -->
          <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
              <thead>
                <tr class="bg-gray-50 text-gray-600">
                  <th class="p-2"><input type="checkbox" :checked="allSelected" @change="toggleAll" /></th>
                  <th class="p-2 text-left">Employee</th>
                  <th class="p-2 text-left">Type</th>
                  <th class="p-2 text-left">Dates</th>
                  <th class="p-2 text-left">Status</th>
                  <th class="p-2 text-left">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="req in filteredRequests" :key="req.id" class="border-b hover:bg-gray-50 align-middle">
                  <td class="py-3 px-5 align-middle"><input type="checkbox" :value="req.id" v-model="selectedRequests" /></td>
                  <td class="py-3 px-5 align-middle">
                    <div class="flex items-center gap-3">
                      <img :src="req.avatar" class="w-8 h-8 rounded-full border" alt="avatar" />
                      <span class="font-medium text-gray-900">{{ req.name }}</span>
                    </div>
                  </td>
                  <td class="py-3 px-5 align-middle">
                    <span class="text-sm">{{ req.type }}</span>
                  </td>
                  <td class="py-3 px-5 align-middle">
                    <span class="text-sm">{{ req.dates }}</span>
                  </td>
                  <td class="py-3 px-5 align-middle">
                    <span v-if="req.status === 'Pending'" class="inline-block px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 font-semibold text-xs">Pending</span>
                    <span v-else-if="req.status === 'Approved'" class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-700 font-semibold text-xs">Approved</span>
                    <span v-else class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-700 font-semibold text-xs">Rejected</span>
                  </td>
                  <td class="py-3 px-5 align-middle">
                    <div class="flex gap-3">
                      <button v-if="req.status === 'Pending'" class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200" @click="approveRequest(req.id)">Approve</button>
                      <button v-if="req.status === 'Pending'" class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200" @click="rejectRequest(req.id)">Reject</button>
                      <button class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200" @click="viewDetails(req.id)">Details</button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <div v-if="filteredRequests.length === 0" class="text-center text-gray-400 py-8">No requests found.</div>
          </div>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>
