<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { LayoutGrid, Calendar, Users, MessageSquare, FileText, Upload, ClipboardList, CalendarDays, Wrench } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { Separator } from '@/components/ui/separator';

// Mock the upcoming events count for the badge
const upcomingEventsCount = 3; // Replace with real logic if needed

// Mock the unread messages count for the badge
const unreadMessagesCount = 12; // Replace with real logic if needed
const messagesBadge = unreadMessagesCount > 9 ? '9+' : unreadMessagesCount;

// Mock the pending leaves count for the badge
const pendingLeavesCount = 4; // Replace with real logic if needed
const leavesBadge = pendingLeavesCount > 9 ? '9+' : pendingLeavesCount;

const overviewNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Calendar',
        href: '/calendar',
        icon: Calendar,
        badge: upcomingEventsCount,
    },
    {
        title: 'Messages',
        href: '/messages',
        icon: MessageSquare,
        badge: messagesBadge,
    },
];

const managementNavItems: NavItem[] = [
    {
        title: 'Employees',
        href: '/employees',
        icon: Users,
    },
    {
        title: 'Attendance',
        href: '/attendance',
        icon: ClipboardList,
    },
    {
        title: 'Leaves',
        href: '/leaves',
        icon: CalendarDays,
        badge: leavesBadge,
    },
    {
        title: 'Configuration',
        href: '/configuration',
        icon: Wrench,
    },
];

const documentsNavItems: NavItem[] = [
    {
        title: 'Documents',
        href: '/documents',
        icon: FileText,
    },
];

const footerNavItems: NavItem[] = [
    // Removed Github Repo and Documentation
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent class="overflow-x-hidden">
            <div class="overflow-y-auto overflow-x-hidden">
                <NavMain :items="overviewNavItems" label="Overview" />
                <Separator class="my-2" />
                <NavMain :items="managementNavItems" label="Management" />
                <Separator class="my-2" />
                <NavMain :items="documentsNavItems" />
            </div>
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
