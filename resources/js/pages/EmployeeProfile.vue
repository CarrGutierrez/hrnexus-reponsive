<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import Card from '@/components/ui/card/Card.vue';
import Button from '@/components/ui/button/Button.vue';
import { ref } from 'vue';

const props = defineProps<{ id: string }>();

// Dummy employee data
const employee = ref({
  id: props.id,
  name: 'John Doe',
  photo: 'https://randomuser.me/api/portraits/men/1.jpg',
  contact: 'john.doe@email.com',
  phone: '+1 555-1234',
  hireDate: '2021-03-15',
  department: 'Engineering',
  roles: ref(['Developer', 'Team Lead']),
  status: 'Active',
});

const allRoles = ['Developer', 'Team Lead', 'QA', 'HR Manager', 'Accountant', 'Sales Rep'];
const newRole = ref('');
function addRole() {
  if (newRole.value && !employee.value.roles.includes(newRole.value)) {
    employee.value.roles.push(newRole.value);
    newRole.value = '';
  }
}
function removeRole(role: string) {
  employee.value.roles = employee.value.roles.filter(r => r !== role);
}

// Dummy attendance heatmap (7x5 grid for a month)
const attendanceHeatmap = Array.from({ length: 5 }, () =>
  Array.from({ length: 7 }, () => Math.floor(Math.random() * 3))
); // 0: absent, 1: present, 2: late
const heatmapColors = ['bg-gray-200', 'bg-green-400', 'bg-yellow-400'];
const heatmapLabels = ['Absent', 'Present', 'Late'];

// Dummy leave balances
const leaveBalances = [
  { type: 'Vacation', balance: 8 },
  { type: 'Sick', balance: 3 },
  { type: 'Emergency', balance: 2 },
];

// Dummy leave history
const leaveHistory = [
  { date: '2025-05-01', type: 'Sick', days: 1, status: 'Approved' },
  { date: '2025-04-15', type: 'Vacation', days: 2, status: 'Approved' },
  { date: '2025-03-10', type: 'Emergency', days: 1, status: 'Rejected' },
];

function formatDate(dateStr: string) {
  const date = new Date(dateStr);
  const monthAbbr = date.toLocaleString('en-US', { month: 'short' });
  const day = date.getDate();
  const year = date.getFullYear();
  return `${monthAbbr} ${day}, ${year}`;
}
</script>

<template>
  <Head :title="employee.name + ' - Profile'" />
  <AppLayout :breadcrumbs="[{ title: 'Employees', href: '/employees' }, { title: employee.name, href: '#' }]">
    <div class="flex flex-col gap-6 p-4">
      <!-- Personal Info -->
      <Card class="p-4 flex flex-col md:flex-row gap-6 items-center md:items-start">
        <img :src="employee.photo" alt="Photo" class="w-24 h-24 rounded-full object-cover border" />
        <div class="flex-1 flex flex-col gap-2">
          <div class="text-xl font-bold">{{ employee.name }}</div>
          <div class="text-gray-600">{{ employee.department }}</div>
          <div class="text-sm text-gray-500">{{ employee.contact }} | {{ employee.phone }}</div>
          <div class="text-sm text-gray-500">Hired: {{ formatDate(employee.hireDate) }}</div>
          <div class="mt-2">
            <span :class="employee.status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-500'" class="rounded px-2 py-0.5 text-xs">{{ employee.status }}</span>
          </div>
        </div>
      </Card>
      <!-- Editable Roles -->
      <Card class="p-4">
        <div class="font-semibold mb-2">Roles</div>
        <div class="flex flex-wrap gap-2 mb-2">
          <span v-for="role in employee.roles" :key="role" class="inline-flex items-center bg-blue-100 text-blue-700 rounded px-2 py-0.5 text-xs">
            {{ role }}
            <button @click="removeRole(role)" class="ml-1 text-red-500 hover:text-red-700">&times;</button>
          </span>
        </div>
        <div class="flex gap-2">
          <select v-model="newRole" class="border rounded px-2 py-1 text-sm">
            <option value="">Add Role</option>
            <option v-for="role in allRoles" :key="role" :value="role" :disabled="employee.roles.includes(role)">{{ role }}</option>
          </select>
          <Button size="sm" variant="outline" @click="addRole" :disabled="!newRole">Add</Button>
        </div>
      </Card>
      <!-- Attendance Heatmap -->
      <Card class="p-4">
        <div class="font-semibold mb-2">Attendance Calendar (Dummy)</div>
        <div class="grid grid-cols-7 gap-1">
          <div v-for="(week, wIdx) in attendanceHeatmap" :key="wIdx" class="contents">
            <div v-for="(val, dIdx) in week" :key="dIdx" :class="['w-8 h-8 rounded', heatmapColors[val], 'border']" :title="heatmapLabels[val]"></div>
          </div>
        </div>
        <div class="flex gap-2 mt-2 text-xs">
          <span v-for="(label, idx) in heatmapLabels" :key="label" class="flex items-center gap-1">
            <span :class="['inline-block w-4 h-4 rounded', heatmapColors[idx], 'border']"></span> {{ label }}
          </span>
        </div>
      </Card>
      <!-- Leave Balances -->
      <Card class="p-4">
        <div class="font-semibold mb-2">Leave Balances</div>
        <div class="flex gap-4">
          <div v-for="leave in leaveBalances" :key="leave.type" class="flex flex-col items-center">
            <span class="text-lg font-bold">{{ leave.balance }}</span>
            <span class="text-xs text-gray-500">{{ leave.type }}</span>
          </div>
        </div>
      </Card>
      <!-- Leave History -->
      <Card class="p-4">
        <div class="font-semibold mb-2">Leave History</div>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm border rounded">
            <thead>
              <tr class="bg-gray-50">
                <th class="p-2 text-left">Date</th>
                <th class="p-2 text-left">Type</th>
                <th class="p-2 text-left">Days</th>
                <th class="p-2 text-left">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="row in leaveHistory" :key="row.date + row.type" class="border-t">
                <td class="p-2">{{ formatDate(row.date) }}</td>
                <td class="p-2">{{ row.type }}</td>
                <td class="p-2">{{ row.days }}</td>
                <td class="p-2">
                  <span :class="row.status === 'Approved' ? 'bg-green-100 text-green-700' : row.status === 'Rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700'" class="rounded px-2 py-0.5 text-xs">{{ row.status }}</span>
                </td>
              </tr>
              <tr v-if="leaveHistory.length === 0">
                <td colspan="4" class="text-center text-gray-400 py-4">No leave history found.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </Card>
    </div>
  </AppLayout>
</template> 