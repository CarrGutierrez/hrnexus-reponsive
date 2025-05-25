<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import Card from '@/components/ui/card/Card.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import Button from '@/components/ui/button/Button.vue';
import { ref, computed } from 'vue';
import { ChevronLeft, ChevronRight, Users } from 'lucide-vue-next';

interface Event {
    id: number;
    title: string;
    time: string;
    type: string;
    attendees: number;
    description: string;
    date: string;
}

const currentDate = ref(new Date());
const selectedEvent = ref<Event | null>(null);

// Sample events data
const events = ref<Event[]>([
    {
        id: 1,
        title: 'Team Meeting',
        time: '3:00 PM - 4:00 PM',
        type: 'Weekly',
        attendees: 25,
        description: 'Weekly team sync-up meeting',
        date: '2025-05-20'
    },
    {
        id: 2,
        title: 'Performance Review',
        time: '2:00 PM - 3:00 PM',
        type: 'Monthly',
        attendees: 2,
        description: 'Monthly performance evaluation',
        date: '2025-05-21'
    },
    {
        id: 3,
        title: 'Training Session',
        time: '10:00 AM - 11:30 AM',
        type: 'Weekly',
        attendees: 15,
        description: 'New employee orientation',
        date: '2025-05-22'
    }
]);

const months = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
];

const daysOfWeek = ['Mon', 'Thu', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

const currentMonthYear = computed(() => {
    return `${months[currentDate.value.getMonth()]} ${currentDate.value.getFullYear()}`;
});

function previousMonth() {
    currentDate.value = new Date(
        currentDate.value.getFullYear(),
        currentDate.value.getMonth() - 1,
        1
    );
}

function nextMonth() {
    currentDate.value = new Date(
        currentDate.value.getFullYear(),
        currentDate.value.getMonth() + 1,
        1
    );
}

function getDaysInMonth(date: Date) {
    return new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
}

function getFirstDayOfMonth(date: Date) {
    return new Date(date.getFullYear(), date.getMonth(), 1).getDay();
}

const calendarDays = computed(() => {
    const days = [];
    const daysInMonth = getDaysInMonth(currentDate.value);
    const firstDay = getFirstDayOfMonth(currentDate.value);

    // Add empty cells for days before the first day of the month
    for (let i = 0; i < firstDay; i++) {
        days.push(null);
    }

    // Add the days of the month
    for (let i = 1; i <= daysInMonth; i++) {
        days.push(i);
    }

    return days;
});

function selectEvent(event: Event) {
    selectedEvent.value = event;
}

function hasEventOnDay(day: number | null): boolean {
    if (day === null) return false;
    const currentYear = currentDate.value.getFullYear();
    const currentMonth = currentDate.value.getMonth() + 1;
    const dateStr = `${currentYear}-${String(currentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    return events.value.some(event => event.date === dateStr);
}

function getEventsForDay(day: number): Event[] {
    const currentYear = currentDate.value.getFullYear();
    const currentMonth = currentDate.value.getMonth() + 1;
    const dateStr = `${currentYear}-${String(currentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    return events.value.filter(event => event.date === dateStr);
}
</script>

<template>
    <Head title="Calendar" />
    <AppLayout :breadcrumbs="[{ title: 'Calendar', href: '/calendar' }]">
        <div class="flex flex-col md:flex-row gap-6 p-4">
            <!-- Calendar Section -->
            <Card class="flex-1 p-6">
                <!-- Calendar Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div class="flex items-center gap-4">
                        <CardTitle class="text-xl">{{ currentMonthYear }}</CardTitle>
                        <div class="flex items-center gap-2">
                            <Button variant="outline" size="sm" @click="previousMonth">
                                <ChevronLeft class="w-4 h-4" />
                            </Button>
                            <Button variant="outline" size="sm" @click="nextMonth">
                                <ChevronRight class="w-4 h-4" />
                            </Button>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <Button variant="outline" size="sm">Today</Button>
                        <Button variant="outline" size="sm">Week</Button>
                        <Button variant="outline" size="sm">Month</Button>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <div class="border rounded-lg">
                    <!-- Days of Week Header -->
                    <div class="grid grid-cols-7 bg-gray-50 dark:bg-gray-800 border-b">
                        <div v-for="day in daysOfWeek" :key="day"
                             class="p-3 text-center text-sm font-medium text-gray-500 border-r last:border-r-0">
                            {{ day }}
                        </div>
                    </div>
                    <!-- Calendar Days -->
                    <div class="grid grid-cols-7">
                        <div v-for="(day, index) in calendarDays" :key="index"
                             class="min-h-[100px] p-3 border-b border-r last:border-r-0 relative align-top">
                            <div v-if="day !== null" 
                                 :class="{'font-bold': hasEventOnDay(day)}"
                                 class="text-sm mb-2">
                                {{ day }}
                            </div>
                            <div v-if="hasEventOnDay(day)" 
                                 class="absolute bottom-2 left-2 right-2">
                                <div v-for="event in getEventsForDay(day ?? 0)" :key="event.id"
                                     class="text-xs bg-blue-100 text-blue-800 rounded p-1 cursor-pointer mb-1"
                                     @click="selectEvent(event)">
                                    {{ event.title }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Card>

            <!-- Event Details Section -->
            <Card class="w-full md:w-80 p-6">
                <CardTitle class="mb-4">Event Details</CardTitle>
                <div v-if="selectedEvent" class="space-y-4">
                  <div>
                    <h3 class="font-semibold text-lg">{{ selectedEvent.title }}</h3>
                    <p class="text-sm text-gray-500">{{ selectedEvent.time }}</p>
                  </div>
                  <div class="flex items-center gap-2 text-sm text-gray-600">
                    <Users class="w-4 h-4" />
                    <span>{{ selectedEvent.attendees }} attendees</span>
                  </div>
                  <div>
                    <p class="text-sm text-gray-600">{{ selectedEvent.description }}</p>
                  </div>
                  <div class="flex gap-2">
                    <Button variant="outline" size="sm">Edit</Button>
                    <Button variant="outline" size="sm" class="text-red-600 hover:text-red-700">Cancel</Button>
                  </div>
                </div>
                <div v-else class="text-center text-gray-500 py-4">
                  Select an event to view details
                </div>
            </Card>
        </div>
    </AppLayout>
</template>

<style scoped>
.calendar-day {
    min-height: 100px;
}
</style> 