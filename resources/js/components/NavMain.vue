<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem, useSidebar } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

defineProps<{
    items: NavItem[];
    label?: string;
}>();

const page = usePage<SharedData>();
const { state } = useSidebar();

// Function to get badge color class
function getBadgeColorClass(title: string) {
    switch (title) {
        case 'Leaves':
            return 'bg-yellow-500';
        case 'Messages':
            return 'bg-blue-500';
        case 'Calendar':
            return 'bg-green-500';
        default:
            return 'bg-primary';
    }
}
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel v-if="label" class="truncate">{{ label }}</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <SidebarMenuButton 
                    as-child 
                    :is-active="item.href === page.url"
                    :tooltip="item.title"
                    class="truncate relative"
                >
                    <Link :href="item.href" class="flex items-center gap-2">
                        <component :is="item.icon" class="shrink-0" />
                        <span class="truncate">{{ item.title }}</span>
                        <!-- Badge for expanded state -->
                        <span v-if="item.badge && state === 'expanded'" 
                            class="ml-auto shrink-0 rounded-full px-2 py-0.5 text-xs"
                            :class="{
                                'bg-yellow-100 text-yellow-800': item.title === 'Leaves',
                                'bg-blue-100 text-blue-800': item.title === 'Messages',
                                'bg-green-100 text-green-800': item.title === 'Calendar',
                                'bg-primary text-primary-foreground': !['Leaves', 'Messages', 'Calendar'].includes(item.title)
                            }"
                        >
                            {{ item.badge }}
                        </span>
                        <!-- Dot indicator for collapsed state -->
                        <span v-if="item.badge && state === 'collapsed'" 
                            class="absolute right-1 top-1 h-2 w-2 rounded-full"
                            :class="getBadgeColorClass(item.title)"
                        />
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
