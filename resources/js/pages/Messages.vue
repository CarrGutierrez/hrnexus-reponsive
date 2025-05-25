<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import Card from '@/components/ui/card/Card.vue';
import Button from '@/components/ui/button/Button.vue';
import { ref, computed, onMounted, nextTick, onUnmounted, watch } from 'vue';
import { Send, Paperclip, Users, Settings, FileText, Plus, X, Search, PenSquare, MoreVertical, Pin, LogOut, Eye } from 'lucide-vue-next';
import Dialog from '@/components/ui/dialog/Dialog.vue';
import DialogContent from '@/components/ui/dialog/DialogContent.vue';
import DialogHeader from '@/components/ui/dialog/DialogHeader.vue';
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue';
import DialogDescription from '@/components/ui/dialog/DialogDescription.vue';

interface Message {
  sender: string;
  text: string;
  time: string;
  timestamp: string;
  avatar?: string;
}

interface User {
  id: number;
  name: string;
  role: string;
  avatar: string;
  isAdmin?: boolean;
}

interface Chat {
  id: number;
  name: string;
  lastMessage: string;
  participants: string[];
  administrators: string[];
  unread: boolean;
  pinned: boolean;
  messages: Message[];
  documents: { name: string; size: string; date: string }[];
  color?: string;
}

const chats = ref<Chat[]>([
  {
    id: 1,
    name: 'Project Team',
    lastMessage: 'Meeting at 2 PM',
    participants: ['John', 'Sarah', 'Mike'],
    administrators: ['John'],
    unread: false,
    pinned: true,
    messages: [
      { 
        sender: 'John', 
        text: 'Hey team!', 
        time: '09:00 AM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 23).toISOString(), // 23h
        avatar: 'https://ui-avatars.com/api/?name=John&background=0D8ABC&color=fff' 
      },
      { 
        sender: 'Sarah', 
        text: 'Hi everyone!', 
        time: '09:01 AM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 22).toISOString(), // 22h
        avatar: 'https://ui-avatars.com/api/?name=Sarah&background=7C3AED&color=fff' 
      },
      { 
        sender: 'Mike', 
        text: 'Good morning!', 
        time: '09:02 AM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 21).toISOString(), // 21h
        avatar: 'https://ui-avatars.com/api/?name=Mike&background=059669&color=fff' 
      },
      { 
        sender: 'John', 
        text: 'Meeting at 2 PM', 
        time: '09:03 AM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 20).toISOString(), // 20h
        avatar: 'https://ui-avatars.com/api/?name=John&background=0D8ABC&color=fff' 
      }
    ],
    documents: [
      { name: 'Project Plan.pdf', size: '2.4 MB', date: '2024-03-15' },
      { name: 'Meeting Notes.docx', size: '156 KB', date: '2024-03-14' }
    ]
  },
  {
    id: 2,
    name: 'HR Updates',
    lastMessage: 'New policy document',
    participants: ['Jane', 'Alex', 'Sarah'],
    administrators: [],
    unread: true,
    pinned: true,
    messages: [
      { 
        sender: 'Jane', 
        text: 'Hello everyone!', 
        time: '10:00 AM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 5).toISOString(), // 5d
        avatar: 'https://ui-avatars.com/api/?name=Jane&background=DC2626&color=fff' 
      },
      { 
        sender: 'Alex', 
        text: 'Hi Jane!', 
        time: '10:01 AM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 4).toISOString(), // 4d
        avatar: 'https://ui-avatars.com/api/?name=Alex&background=4B5563&color=fff' 
      },
      { 
        sender: 'Sarah', 
        text: 'Thanks Jane!', 
        time: '10:01 AM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 3).toISOString(), // 3d
        avatar: 'https://ui-avatars.com/api/?name=Sarah&background=7C3AED&color=fff' 
      }
    ],
    documents: [
      { name: 'HR Policy 2024.pdf', size: '1.8 MB', date: '2024-03-13' }
    ]
  },
  {
    id: 3,
    name: 'Marketing Team',
    lastMessage: 'Campaign review',
    participants: ['Mike', 'Sarah', 'Alex'],
    administrators: ['Mike'],
    unread: false,
    pinned: false,
    messages: [
      { 
        sender: 'Mike', 
        text: 'Campaign review at 11 AM', 
        time: '11:00 AM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 7).toISOString(), // 1w
        avatar: 'https://ui-avatars.com/api/?name=Mike&background=059669&color=fff' 
      },
      { 
        sender: 'Sarah', 
        text: 'I will be there!', 
        time: '11:01 AM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 6).toISOString(), // 6d
        avatar: 'https://ui-avatars.com/api/?name=Sarah&background=7C3AED&color=fff' 
      },
      { 
        sender: 'Alex', 
        text: 'Lets keep it up!', 
        time: '12:01 PM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 5).toISOString(), // 5d
        avatar: 'https://ui-avatars.com/api/?name=Alex&background=4B5563&color=fff' 
      }
    ],
    documents: [
      { name: 'Q2 Campaign.pdf', size: '3.2 MB', date: '2024-03-12' },
      { name: 'Brand Guidelines.pdf', size: '4.1 MB', date: '2024-03-11' }
    ]
  },
  {
    id: 4,
    name: 'Development Team',
    lastMessage: 'Code review needed',
    participants: ['John', 'Mike', 'Alex'],
    administrators: ['John'],
    unread: true,
    pinned: false,
    messages: [
      { 
        sender: 'John', 
        text: 'Code review needed', 
        time: '01:00 PM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 14).toISOString(), // 2w
        avatar: 'https://ui-avatars.com/api/?name=John&background=0D8ABC&color=fff' 
      },
      { 
        sender: 'Mike', 
        text: 'I will take a look', 
        time: '01:01 PM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 13).toISOString(), // 13d
        avatar: 'https://ui-avatars.com/api/?name=Mike&background=059669&color=fff' 
      },
      { 
        sender: 'Alex', 
        text: 'Thanks Mike!', 
        time: '01:02 PM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 12).toISOString(), // 12d
        avatar: 'https://ui-avatars.com/api/?name=Alex&background=4B5563&color=fff' 
      }
    ],
    documents: [
      { name: 'API Documentation.pdf', size: '2.1 MB', date: '2024-03-10' },
      { name: 'System Architecture.docx', size: '1.5 MB', date: '2024-03-09' }
    ]
  },
  {
    id: 5,
    name: 'Product Development',
    lastMessage: 'New feature discussion',
    participants: ['John', 'Sarah', 'Mike', 'Alex'],
    administrators: ['John', 'Sarah'],
    unread: true,
    pinned: false,
    messages: [
      { 
        sender: 'John', 
        text: 'Let\'s discuss the new feature', 
        time: '02:00 PM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 30).toISOString(), // 1mo
        avatar: 'https://ui-avatars.com/api/?name=John&background=0D8ABC&color=fff' 
      },
      { 
        sender: 'Sarah', 
        text: 'I have some ideas', 
        time: '02:01 PM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 29).toISOString(), // 29d
        avatar: 'https://ui-avatars.com/api/?name=Sarah&background=7C3AED&color=fff' 
      }
    ],
    documents: [
      { name: 'Feature Spec.pdf', size: '1.2 MB', date: '2024-03-16' }
    ]
  },
  {
    id: 6,
    name: 'Customer Support',
    lastMessage: 'Ticket #1234 resolved',
    participants: ['Jane', 'Mike', 'Alex', 'Sarah'],
    administrators: ['Jane'],
    unread: false,
    pinned: false,
    messages: [
      { 
        sender: 'Jane', 
        text: 'Ticket #1234 has been resolved', 
        time: '03:00 PM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 60).toISOString(), // 2mo
        avatar: 'https://ui-avatars.com/api/?name=Jane&background=DC2626&color=fff' 
      },
      { 
        sender: 'Mike', 
        text: 'Great work!', 
        time: '03:01 PM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 59).toISOString(), // 59d
        avatar: 'https://ui-avatars.com/api/?name=Mike&background=059669&color=fff' 
      }
    ],
    documents: [
      { name: 'Support Guidelines.pdf', size: '2.8 MB', date: '2024-03-15' }
    ]
  },
  {
    id: 7,
    name: 'Design Team',
    lastMessage: 'New UI mockups ready',
    participants: ['Sarah', 'Alex', 'Mike', 'John'],
    administrators: ['Sarah'],
    unread: true,
    pinned: false,
    messages: [
      { 
        sender: 'Sarah', 
        text: 'New UI mockups are ready for review', 
        time: '04:00 PM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 365).toISOString(), // 1y
        avatar: 'https://ui-avatars.com/api/?name=Sarah&background=7C3AED&color=fff' 
      },
      { 
        sender: 'Alex', 
        text: 'Looking forward to it!', 
        time: '04:01 PM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 364).toISOString(), // 364d
        avatar: 'https://ui-avatars.com/api/?name=Alex&background=4B5563&color=fff' 
      }
    ],
    documents: [
      { name: 'UI Mockups.pdf', size: '5.6 MB', date: '2024-03-16' },
      { name: 'Design System.pdf', size: '3.2 MB', date: '2024-03-15' }
    ]
  },
  {
    id: 8,
    name: 'Sales Team',
    lastMessage: 'Q2 targets discussion',
    participants: ['Mike', 'Jane', 'John', 'Sarah'],
    administrators: ['Mike'],
    unread: false,
    pinned: false,
    messages: [
      { 
        sender: 'Mike', 
        text: 'Let\'s discuss Q2 targets', 
        time: '05:00 PM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 365 * 2).toISOString(), // 2y
        avatar: 'https://ui-avatars.com/api/?name=Mike&background=059669&color=fff' 
      },
      { 
        sender: 'Jane', 
        text: 'I\'ll prepare the numbers', 
        time: '05:01 PM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 365 * 2 - 1000 * 60 * 60 * 24).toISOString(), // 2y-1d
        avatar: 'https://ui-avatars.com/api/?name=Jane&background=DC2626&color=fff' 
      }
    ],
    documents: [
      { name: 'Q2 Sales Targets.xlsx', size: '1.1 MB', date: '2024-03-16' }
    ]
  },
  {
    id: 9,
    name: 'Research & Development',
    lastMessage: 'New technology evaluation',
    participants: ['John', 'Alex', 'Sarah', 'Mike'],
    administrators: ['John', 'Alex'],
    unread: true,
    pinned: false,
    messages: [
      { 
        sender: 'John', 
        text: 'New tech stack evaluation needed', 
        time: '06:00 PM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 365 * 3).toISOString(), // 3y
        avatar: 'https://ui-avatars.com/api/?name=John&background=0D8ABC&color=fff' 
      },
      { 
        sender: 'Alex', 
        text: 'I\'ll start the research', 
        time: '06:01 PM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 365 * 3 - 1000 * 60 * 60 * 24).toISOString(), // 3y-1d
        avatar: 'https://ui-avatars.com/api/?name=Alex&background=4B5563&color=fff' 
      }
    ],
    documents: [
      { name: 'Tech Stack Analysis.pdf', size: '4.2 MB', date: '2024-03-16' }
    ]
  },
  {
    id: 10,
    name: 'Quality Assurance',
    lastMessage: 'Test results ready',
    participants: ['Sarah', 'Mike', 'Jane', 'Alex'],
    administrators: ['Sarah'],
    unread: false,
    pinned: false,
    messages: [
      { 
        sender: 'Sarah', 
        text: 'Test results are ready for review', 
        time: '07:00 PM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 365 * 5).toISOString(), // 5y
        avatar: 'https://ui-avatars.com/api/?name=Sarah&background=7C3AED&color=fff' 
      },
      { 
        sender: 'Mike', 
        text: 'I\'ll check them now', 
        time: '07:01 PM', 
        timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24 * 365 * 5 - 1000 * 60 * 60 * 24).toISOString(), // 5y-1d
        avatar: 'https://ui-avatars.com/api/?name=Mike&background=059669&color=fff' 
      }
    ],
    documents: [
      { name: 'Test Results.pdf', size: '2.3 MB', date: '2024-03-16' }
    ]
  }
]);

const search = ref('');
const filterParticipant = ref('');

const filteredChats = computed(() => {
  const filtered = chats.value.filter(chat => {
    const matchesName = chat.name.toLowerCase().includes(search.value.toLowerCase());
    const matchesParticipant = !filterParticipant.value || chat.participants.includes(filterParticipant.value);
    return matchesName && matchesParticipant;
  });
  
  return {
    pinned: filtered.filter(chat => chat.pinned),
    general: filtered.filter(chat => !chat.pinned)
  };
});

const selectedChatId = ref(chats.value[0]?.id || null);
const selectedChat = computed(() => chats.value.find(c => c.id === selectedChatId.value));

const showCreateModal = ref(false);
const newGroupName = ref('');
const selectedColor = ref('');
const isCustomColor = ref(false);
const customColor = ref('#3B82F6');
const selectedMembers = ref<User[]>([]);
const selectedAdmins = ref<User[]>([]);
const searchMember = ref('');

function createGroupChat() {
  if (newGroupName.value && selectedMembers.value.length > 0) {
    const newChat: Chat = {
      id: chats.value.length + 1,
      name: newGroupName.value,
      lastMessage: 'Group created',
      participants: selectedMembers.value.map(m => m.name),
      administrators: selectedAdmins.value.map(m => m.name),
      unread: false,
      pinned: false,
      messages: [
        { 
          sender: 'System', 
          text: 'Group created', 
          time: 'Now',
          timestamp: new Date().toISOString(),
          avatar: 'https://ui-avatars.com/api/?name=System&background=4B5563&color=fff'
        }
      ],
      documents: [],
      color: isCustomColor.value ? customColor.value : selectedColor.value
    };
    
    chats.value.unshift(newChat);
    selectedChatId.value = newChat.id;
    
    // Reset form
    newGroupName.value = '';
    selectedColor.value = '';
    isCustomColor.value = false;
    customColor.value = '#3B82F6';
    selectedMembers.value = [];
    selectedAdmins.value = [];
    searchMember.value = '';
    showCreateModal.value = false;
  }
}

const filteredAvailableUsers = computed(() => {
  return availableUsers.filter(user => 
    !selectedMembers.value.some(m => m.id === user.id) &&
    user.name.toLowerCase().includes(searchMember.value.toLowerCase())
  );
});

function toggleAdmin(member: User) {
  const isAdmin = selectedAdmins.value.some(m => m.id === member.id);
  if (isAdmin) {
    selectedAdmins.value = selectedAdmins.value.filter(m => m.id !== member.id);
  } else {
    selectedAdmins.value.push(member);
  }
}

const availableUsers: User[] = [
  { id: 1, name: 'John Smith', role: 'Developer', avatar: 'https://ui-avatars.com/api/?name=John+Smith&background=0D8ABC&color=fff' },
  { id: 2, name: 'Emma Wilson', role: 'Designer', avatar: 'https://ui-avatars.com/api/?name=Emma+Wilson&background=7C3AED&color=fff' },
  { id: 3, name: 'Michael Brown', role: 'Project Manager', avatar: 'https://ui-avatars.com/api/?name=Michael+Brown&background=059669&color=fff' },
  { id: 4, name: 'Sarah Davis', role: 'Developer', avatar: 'https://ui-avatars.com/api/?name=Sarah+Davis&background=DC2626&color=fff' },
  { id: 5, name: 'David Miller', role: 'Designer', avatar: 'https://ui-avatars.com/api/?name=David+Miller&background=4B5563&color=fff' }
];

const showMembersModal = ref(false);
const showAddMembersModal = ref(false);
const showGroupSettingsModal = ref(false);
const showDocumentsModal = ref(false);
const showConfirmKickModal = ref(false);
const memberToKick = ref<string | null>(null);

function confirmKickMember(member: string) {
  memberToKick.value = member;
  showConfirmKickModal.value = true;
}

function kickMember() {
  if (selectedChat.value && memberToKick.value) {
    selectedChat.value.participants = selectedChat.value.participants.filter(p => p !== memberToKick.value);
    memberToKick.value = null;
    showConfirmKickModal.value = false;
  }
}

function addSelectedMembers() {
  if (selectedChat.value && selectedMembers.value.length > 0) {
    selectedChat.value.participants.push(...selectedMembers.value.map(m => m.name));
    selectedMembers.value = [];
    showAddMembersModal.value = false;
  }
}

function removeSelectedMember(member: User) {
  selectedMembers.value = selectedMembers.value.filter(m => m.id !== member.id);
}

function promoteToAdmin(member: string) {
  if (selectedChat.value && !selectedChat.value.administrators.includes(member)) {
    selectedChat.value.administrators.push(member);
  }
}

function demoteAdmin(member: string) {
  if (selectedChat.value) {
    selectedChat.value.administrators = selectedChat.value.administrators.filter(a => a !== member);
  }
}

const showSearchModal = ref(false);
const messageSearch = ref('');
const searchResults = computed(() => {
  if (!selectedChat.value || !messageSearch.value) return [];
  
  return selectedChat.value.messages.filter(message => 
    message.text.toLowerCase().includes(messageSearch.value.toLowerCase()) ||
    message.sender.toLowerCase().includes(messageSearch.value.toLowerCase())
  );
});

const showChatMenu = ref<number | null>(null);

function toggleChatMenu(chatId: number, event: Event) {
  event.stopPropagation();
  showChatMenu.value = showChatMenu.value === chatId ? null : chatId;
}

function closeChatMenu() {
  showChatMenu.value = null;
}

function togglePin(chat: Chat, event: Event) {
  event.stopPropagation();
  chat.pinned = !chat.pinned;
  closeChatMenu();
}

const showLeaveGroupModal = ref(false);
const groupToLeave = ref<Chat | null>(null);

function leaveGroup(chat: Chat, event: Event) {
  event.stopPropagation();
  groupToLeave.value = chat;
  showLeaveGroupModal.value = true;
}

function confirmLeaveGroup() {
  if (groupToLeave.value) {
    chats.value = chats.value.filter(c => c.id !== groupToLeave.value?.id);
    
    if (selectedChatId.value === groupToLeave.value.id) {
      selectedChatId.value = chats.value[0]?.id || null;
    }
    
    groupToLeave.value = null;
    showLeaveGroupModal.value = false;
  }
}

onMounted(() => {
  document.addEventListener('click', closeChatMenu);
});

onUnmounted(() => {
  document.removeEventListener('click', closeChatMenu);
});

function formatRelativeTime(time: string): string {
  const now = new Date();
  const messageTime = new Date(time);
  const diffInSeconds = Math.floor((now.getTime() - messageTime.getTime()) / 1000);

  if (diffInSeconds < 60) return 'now';
  if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m`;
  if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h`;
  if (diffInSeconds < 604800) return `${Math.floor(diffInSeconds / 86400)}d`;
  if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 604800)}w`;
  if (diffInSeconds < 31536000) return `${Math.floor(diffInSeconds / 2592000)}mo`;
  return `${Math.floor(diffInSeconds / 31536000)}y`;
}

function markChatAsSeen(chatId: number) {
  const chat = chats.value.find(c => c.id === chatId);
  if (chat) {
    chat.unread = false;
  }
}

const predefinedColors = [
  { name: 'Blue', value: 'blue', class: 'bg-blue-500', border: 'border-blue-500', gradient: 'from-blue-100/90 via-blue-100/70 via-blue-50/50 via-blue-50/30 to-transparent' },
  { name: 'Purple', value: 'purple', class: 'bg-purple-500', border: 'border-purple-500', gradient: 'from-purple-100/90 via-purple-100/70 via-purple-50/50 via-purple-50/30 to-transparent' },
  { name: 'Emerald', value: 'emerald', class: 'bg-emerald-500', border: 'border-emerald-500', gradient: 'from-emerald-100/90 via-emerald-100/70 via-emerald-50/50 via-emerald-50/30 to-transparent' },
  { name: 'Rose', value: 'rose', class: 'bg-rose-500', border: 'border-rose-500', gradient: 'from-rose-100/90 via-rose-100/70 via-rose-50/50 via-rose-50/30 to-transparent' },
  { name: 'Amber', value: 'amber', class: 'bg-amber-500', border: 'border-amber-500', gradient: 'from-amber-100/90 via-amber-100/70 via-amber-50/50 via-amber-50/30 to-transparent' },
  { name: 'Indigo', value: 'indigo', class: 'bg-indigo-500', border: 'border-indigo-500', gradient: 'from-indigo-100/90 via-indigo-100/70 via-indigo-50/50 via-indigo-50/30 to-transparent' },
  { name: 'Teal', value: 'teal', class: 'bg-teal-500', border: 'border-teal-500', gradient: 'from-teal-100/90 via-teal-100/70 via-teal-50/50 via-teal-50/30 to-transparent' },
  { name: 'Pink', value: 'pink', class: 'bg-pink-500', border: 'border-pink-500', gradient: 'from-pink-100/90 via-pink-100/70 via-pink-50/50 via-pink-50/30 to-transparent' },
  { name: 'Orange', value: 'orange', class: 'bg-orange-500', border: 'border-orange-500', gradient: 'from-orange-100/90 via-orange-100/70 via-orange-50/50 via-orange-50/30 to-transparent' },
  { name: 'Cyan', value: 'cyan', class: 'bg-cyan-500', border: 'border-cyan-500', gradient: 'from-cyan-100/90 via-cyan-100/70 via-cyan-50/50 via-cyan-50/30 to-transparent' }
];

function calculateGradient(color: string) {
  // Convert hex to RGB
  const r = parseInt(color.slice(1, 3), 16);
  const g = parseInt(color.slice(3, 5), 16);
  const b = parseInt(color.slice(5, 7), 16);

  // Create lighter versions for gradient
  const lighter1 = `rgb(${Math.min(r + 40, 255)}, ${Math.min(g + 40, 255)}, ${Math.min(b + 40, 255)})`;
  const lighter2 = `rgb(${Math.min(r + 20, 255)}, ${Math.min(g + 20, 255)}, ${Math.min(b + 20, 255)})`;
  const lighter3 = `rgb(${Math.min(r + 10, 255)}, ${Math.min(g + 10, 255)}, ${Math.min(b + 10, 255)})`;

  return {
    border: `border-[${color}]`,
    gradient: `from-[${lighter1}]/90 via-[${lighter2}]/70 via-[${lighter3}]/50 via-[${lighter3}]/30 to-transparent`
  };
}

function getChatColor(index: number) {
  if (selectedChat.value?.color) {
    if (selectedChat.value.color.startsWith('#')) {
      // Custom color
      return calculateGradient(selectedChat.value.color);
    } else {
      // Predefined color
      const color = predefinedColors.find(c => c.value === selectedChat.value?.color) || predefinedColors[0];
      return { border: color.border, gradient: color.gradient };
    }
  }
  return predefinedColors[index % predefinedColors.length];
}

function updateGroupSettings() {
  if (selectedChat.value) {
    if (newGroupName.value) {
      selectedChat.value.name = newGroupName.value;
    }
    if (selectedColor.value || customColor.value) {
      selectedChat.value.color = isCustomColor.value ? customColor.value : selectedColor.value;
    }
    newGroupName.value = '';
    selectedColor.value = '';
    customColor.value = '#3B82F6';
    isCustomColor.value = false;
    showGroupSettingsModal.value = false;
  }
}

const groupNameInput = ref<HTMLInputElement | null>(null);

watch(showGroupSettingsModal, (newValue) => {
  if (newValue && selectedChat.value) {
    newGroupName.value = selectedChat.value.name;
    // Ensure the input doesn't get focus
    nextTick(() => {
      if (groupNameInput.value) {
        groupNameInput.value.blur();
      }
    });
  }
});

const documentSearch = ref('');

const filteredDocuments = computed(() => {
  if (!selectedChat.value || !documentSearch.value) return selectedChat.value?.documents || [];
  
  const searchTerm = documentSearch.value.toLowerCase();
  return selectedChat.value.documents.filter(doc => 
    doc.name.toLowerCase().includes(searchTerm) ||
    doc.size.toLowerCase().includes(searchTerm) ||
    doc.date.toLowerCase().includes(searchTerm)
  );
});

// Add back message-related refs and functions
const messageInput = ref('');
const textareaRef = ref<HTMLTextAreaElement | null>(null);
const isSingleLine = ref(true);

function updateIsSingleLine() {
  if (textareaRef.value) {
    isSingleLine.value = textareaRef.value.scrollHeight <= 48; // 48px = h-12
  }
}

onMounted(() => {
  nextTick(updateIsSingleLine);
  document.addEventListener('click', closeChatMenu);
});

function sendMessage() {
  if (messageInput.value && selectedChat.value) {
    const newMessage = { 
      sender: 'You', 
      text: messageInput.value, 
      time: 'Now',
      timestamp: new Date().toISOString(),
      avatar: 'https://ui-avatars.com/api/?name=You&background=2563EB&color=fff'
    };
    selectedChat.value.messages.push(newMessage);
    // Update the lastMessage property
    selectedChat.value.lastMessage = messageInput.value;
    messageInput.value = '';
    if (textareaRef.value) {
      textareaRef.value.style.height = '48px'; // Reset to default height (h-12)
    }
    nextTick(updateIsSingleLine);
  }
}

function attachFile() {
  // Dummy attachment logic
  alert('Attach file (dummy)');
}
</script>

<template>
  <Head title="Messages" />
  <AppLayout :breadcrumbs="[{ title: 'Messages', href: '/messages' }]">
    <div class="flex flex-col md:flex-row gap-6 p-6 h-[calc(100vh-5rem)]">
      <!-- Sidebar: Group Chats -->
      <div class="w-full md:w-1/3 lg:w-1/4 flex flex-col h-full">
        <Card class="p-6 flex flex-col h-full">
          <div class="flex items-center justify-between flex-none mb-4">
            <h1 class="text-2xl font-bold">Chats</h1>
            <Button 
              size="icon" 
              variant="outline"
              @click="showCreateModal = true"
              class="p-2"
            >
              <PenSquare class="w-5 h-5" />
            </Button>
          </div>
          <div class="flex items-center gap-2 flex-none mb-4">
            <div class="relative flex-1">
              <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
              <input 
                v-model="search" 
                type="text" 
                placeholder="Search group..." 
                class="w-full pl-10 pr-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
              />
            </div>
          </div>
          <div class="overflow-y-auto flex-1 min-h-0 space-y-6">
            <!-- Pinned Section -->
            <div v-if="filteredChats.pinned.length > 0" class="mb-4">
              <div class="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">Pinned</div>
              <div v-for="(chat, index) in filteredChats.pinned" :key="chat.id" 
                @click="() => { selectedChatId = chat.id; markChatAsSeen(chat.id); }" 
                :class="[
                  'p-3 rounded cursor-pointer flex items-center group relative border-l-4 transition-all duration-200',
                  selectedChatId === chat.id 
                    ? `${getChatColor(index).border} bg-gradient-to-r ${getChatColor(index).gradient} bg-[length:200%_100%]` 
                    : 'border-transparent hover:bg-gray-50 hover:border-gray-200'
                ]">
                <div class="flex-1 min-w-0">
                  <div class="font-semibold flex items-center gap-2">
                    <span class="truncate">{{ chat.name }}</span>
                    <span v-if="chat.unread" class="inline-block w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                  </div>
                  <div class="text-sm truncate flex items-center gap-1" :class="chat.unread ? 'font-bold text-black' : 'font-normal text-black/70'">
                    <span :class="chat.unread ? 'font-bold' : 'font-normal'">{{ chat.messages[chat.messages.length - 1]?.sender }}:</span>
                    <span class="truncate" :class="chat.unread ? 'font-bold' : 'font-normal'">{{ chat.lastMessage }}</span>
                    <span class="text-gray-400 text-xs whitespace-nowrap font-normal">• {{ formatRelativeTime(chat.messages[chat.messages.length - 1]?.timestamp || '') }}</span>
                  </div>
                </div>
                <Button 
                  size="icon" 
                  variant="ghost" 
                  class="opacity-0 group-hover:opacity-100 transition-opacity p-1"
                  @click="toggleChatMenu(chat.id, $event)"
                >
                  <MoreVertical class="w-4 h-4" />
                </Button>
                <!-- Chat Menu -->
                <div v-if="showChatMenu === chat.id" 
                  class="absolute right-0 top-full mt-1 w-48 bg-white rounded-lg shadow-lg border py-1 z-10"
                  @click.stop
                >
                  <button 
                    class="w-full px-4 py-2 text-sm text-left hover:bg-gray-100 flex items-center gap-2"
                    @click="togglePin(chat, $event)"
                  >
                    <Pin class="w-4 h-4" :class="chat.pinned ? 'text-blue-500' : 'text-gray-500'" />
                    {{ chat.pinned ? 'Unpin' : 'Pin' }}
                  </button>
                  <button 
                    class="w-full px-4 py-2 text-sm text-left hover:bg-gray-100 flex items-center gap-2 text-red-500"
                    @click="leaveGroup(chat, $event)"
                  >
                    <LogOut class="w-4 h-4" />
                    Leave Group
                  </button>
                </div>
              </div>
            </div>

            <!-- General Section -->
            <div v-if="filteredChats.general.length > 0">
              <div class="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">General</div>
              <div v-for="(chat, index) in filteredChats.general" :key="chat.id" 
                @click="() => { selectedChatId = chat.id; markChatAsSeen(chat.id); }" 
                :class="[
                  'p-3 rounded cursor-pointer flex items-center group relative border-l-4 transition-all duration-200',
                  selectedChatId === chat.id 
                    ? `${getChatColor(index + filteredChats.pinned.length).border} bg-gradient-to-r ${getChatColor(index + filteredChats.pinned.length).gradient} bg-[length:200%_100%]` 
                    : 'border-transparent hover:bg-gray-50 hover:border-gray-200'
                ]">
                <div class="flex-1 min-w-0">
                  <div class="font-semibold flex items-center gap-2">
                    <span class="truncate">{{ chat.name }}</span>
                    <span v-if="chat.unread" class="inline-block w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                  </div>
                  <div class="text-sm truncate flex items-center gap-1" :class="chat.unread ? 'font-bold text-black' : 'font-normal text-black/70'">
                    <span :class="chat.unread ? 'font-bold' : 'font-normal'">{{ chat.messages[chat.messages.length - 1]?.sender }}:</span>
                    <span class="truncate" :class="chat.unread ? 'font-bold' : 'font-normal'">{{ chat.lastMessage }}</span>
                    <span class="text-gray-400 text-xs whitespace-nowrap font-normal">• {{ formatRelativeTime(chat.messages[chat.messages.length - 1]?.timestamp || '') }}</span>
                  </div>
                </div>
                <Button 
                  size="icon" 
                  variant="ghost" 
                  class="opacity-0 group-hover:opacity-100 transition-opacity p-1"
                  @click="toggleChatMenu(chat.id, $event)"
                >
                  <MoreVertical class="w-4 h-4" />
                </Button>
                <!-- Chat Menu -->
                <div v-if="showChatMenu === chat.id" 
                  class="absolute right-0 top-full mt-1 w-48 bg-white rounded-lg shadow-lg border py-1 z-10"
                  @click.stop
                >
                  <button 
                    class="w-full px-4 py-2 text-sm text-left hover:bg-gray-100 flex items-center gap-2"
                    @click="togglePin(chat, $event)"
                  >
                    <Pin class="w-4 h-4" :class="chat.pinned ? 'text-blue-500' : 'text-gray-500'" />
                    {{ chat.pinned ? 'Unpin' : 'Pin' }}
                  </button>
                  <button 
                    class="w-full px-4 py-2 text-sm text-left hover:bg-gray-100 flex items-center gap-2 text-red-500"
                    @click="leaveGroup(chat, $event)"
                  >
                    <LogOut class="w-4 h-4" />
                    Leave Group
                  </button>
                </div>
              </div>
            </div>

            <div v-if="filteredChats.pinned.length === 0 && filteredChats.general.length === 0" class="text-center text-gray-400 py-4">
              No group chats found.
            </div>
          </div>
        </Card>
      </div>
      <!-- Main Chat Area -->
      <div class="w-full md:w-2/3 lg:w-3/4 flex flex-col h-full">
        <Card class="p-6 flex flex-col h-full">
          <div v-if="selectedChat" class="flex flex-col h-full">
            <div class="flex items-center justify-between gap-2 mb-2 flex-none">
              <div class="font-bold text-lg">{{ selectedChat.name }}</div>
              <div class="flex items-center gap-2">
                <Button 
                  size="sm" 
                  variant="outline" 
                  @click="showSearchModal = true"
                  class="p-2"
                  title="Search Messages"
                >
                  <Search class="w-4 h-4" />
                </Button>
                <Button 
                  size="sm" 
                  variant="outline" 
                  @click="showDocumentsModal = true"
                  class="p-2"
                  title="Documents"
                >
                  <FileText class="w-4 h-4" />
                </Button>
                <Button 
                  size="sm" 
                  variant="outline" 
                  @click="showMembersModal = true"
                  class="p-2"
                  title="Show Members"
                >
                  <Users class="w-4 h-4" />
                </Button>
                <Button 
                  size="sm" 
                  variant="outline" 
                  @click="showGroupSettingsModal = true"
                  class="p-2"
                  title="Group Settings"
                >
                  <Settings class="w-4 h-4" />
                </Button>
              </div>
            </div>
            <div class="flex-1 overflow-y-auto min-h-0 bg-gray-50 rounded p-4 mb-2 space-y-6">
              <div v-for="(msg, idx) in selectedChat.messages" :key="idx">
                <div class="flex items-start gap-3">
                  <img :src="msg.avatar" :alt="msg.sender" class="w-8 h-8 rounded-full" />
                  <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                      <span class="font-semibold text-sm">{{ msg.sender }}</span>
                      <span class="text-xs text-gray-400">{{ msg.time }}</span>
                    </div>
                    <div class="bg-white rounded-lg p-3 shadow-sm max-w-[80%] relative">
                      <div class="absolute -left-2 top-3 w-4 h-4 bg-white transform rotate-45"></div>
                      <p class="text-sm">{{ msg.text }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="flex gap-3 mt-auto items-end relative flex-none pt-4">
              <Button size="icon" variant="outline" @click="attachFile" class="p-2 w-12 h-12 flex items-center justify-center">
                <Paperclip class="w-5 h-5" />
              </Button>
              <div class="relative flex-1">
                <textarea
                  ref="textareaRef"
                  v-model="messageInput"
                  placeholder="Type a message..."
                  rows="1"
                  maxlength="1000"
                  class="block border rounded px-3 py-2 text-sm w-full resize-none min-h-12 h-12 max-h-[144px] leading-normal"
                  @input="(e: Event) => { 
                    const t = e.target as HTMLTextAreaElement; 
                    t.style.height = 'auto'; 
                    t.style.height = Math.min(t.scrollHeight, 144) + 'px'; 
                  }"
                  @keyup.enter.exact="sendMessage"
                />
              </div>
              <Button size="icon" variant="outline" @click="sendMessage" :disabled="!messageInput" class="p-2 w-12 h-12 flex items-center justify-center">
                <Send class="w-6 h-6" />
              </Button>
            </div>
          </div>
          <div v-else class="flex-1 flex items-center justify-center text-gray-400">Select a chat to view messages.</div>
        </Card>
      </div>
      <!-- Create Chat Modal -->
      <Dialog v-model:open="showCreateModal">
        <DialogContent class="max-w-2xl">
          <DialogHeader>
            <DialogTitle>Create New Group Chat</DialogTitle>
            <DialogDescription>Create a new group chat and add members.</DialogDescription>
          </DialogHeader>
          
          <div class="mt-4 space-y-6">
            <!-- Group Name -->
            <div>
              <label class="block text-sm font-medium mb-1">Group Name</label>
              <input 
                v-model="newGroupName" 
                type="text" 
                placeholder="Enter group name..."
                class="w-full border rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
              />
            </div>

            <!-- Group Color -->
            <div>
              <label class="block text-sm font-medium mb-2">Group Color</label>
              <div class="space-y-3">
                <!-- Predefined Colors -->
                <div class="flex flex-wrap gap-2">
                  <button
                    v-for="color in predefinedColors"
                    :key="color.value"
                    @click="() => { selectedColor = color.value; isCustomColor = false; }"
                    :class="[
                      'w-8 h-8 rounded-full border-2 transition-all',
                      color.class,
                      selectedColor === color.value ? 'ring-2 ring-offset-2 ring-primary scale-110' : 'hover:scale-110',
                      isCustomColor ? 'opacity-50' : ''
                    ]"
                    :title="color.name"
                  />
                  <!-- Custom Color Button -->
                  <button
                    @click="() => { isCustomColor = true; selectedColor = ''; }"
                    :class="[
                      'w-8 h-8 rounded-full border-2 border-dashed border-gray-300 flex items-center justify-center transition-all',
                      isCustomColor ? 'ring-2 ring-offset-2 ring-primary scale-110 bg-gray-50' : 'hover:bg-gray-50',
                      selectedColor ? 'opacity-50' : ''
                    ]"
                    title="Custom Color"
                  >
                    <Plus class="w-4 h-4 text-gray-500" />
                  </button>
                </div>

                <!-- Custom Color Picker -->
                <div v-if="isCustomColor" class="mt-2 p-3 border rounded-lg bg-gray-50">
                  <div class="flex items-center gap-3">
                    <input
                      v-model="customColor"
                      type="color"
                      class="w-8 h-8 rounded-full cursor-pointer border-2 border-gray-200"
                    />
                    <div class="flex-1">
                      <div class="text-sm font-medium mb-1">Custom Color</div>
                      <div class="text-sm text-gray-500">Select a custom color for this group</div>
                    </div>
                    <Button 
                      size="sm" 
                      variant="ghost" 
                      @click="() => { isCustomColor = false; customColor = '#3B82F6'; }"
                    >
                      <X class="w-4 h-4" />
                    </Button>
                  </div>
                </div>

                <!-- Color Preview -->
                <div v-if="selectedColor || (isCustomColor && customColor)" class="mt-2 p-2 rounded border">
                  <div class="text-sm font-medium mb-1">Preview</div>
                  <div 
                    class="h-8 rounded border-l-4 transition-all"
                    :class="[
                      isCustomColor 
                        ? calculateGradient(customColor).border 
                        : predefinedColors.find(c => c.value === selectedColor)?.border
                    ]"
                    :style="{
                      background: isCustomColor 
                        ? `linear-gradient(to right, ${calculateGradient(customColor).gradient})`
                        : `linear-gradient(to right, ${predefinedColors.find(c => c.value === selectedColor)?.gradient})`
                    }"
                  ></div>
                </div>
              </div>
            </div>

            <!-- Member Selection -->
            <div>
              <div class="flex justify-between items-center mb-2">
                <label class="block text-sm font-medium">Add Members</label>
                <div class="relative w-64">
                  <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
                  <input 
                    v-model="searchMember" 
                    type="text" 
                    placeholder="Search members..." 
                    class="w-full pl-10 pr-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
                  />
                </div>
              </div>

              <!-- Selected Members -->
              <div v-if="selectedMembers.length > 0" class="mb-4">
                <div class="flex flex-wrap gap-2">
                  <div v-for="member in selectedMembers" :key="member.id" 
                    class="flex items-center gap-2 bg-gray-50 rounded-full px-3 py-1.5">
                    <img :src="member.avatar" :alt="member.name" class="w-6 h-6 rounded-full" />
                    <span class="text-sm">{{ member.name }}</span>
                    <div class="flex items-center gap-1">
                      <Button 
                        size="sm" 
                        variant="ghost" 
                        :class="selectedAdmins.some(m => m.id === member.id) ? 'text-blue-600' : 'text-gray-500'"
                        @click="toggleAdmin(member)"
                        title="Toggle Admin"
                      >
                        <Users class="w-4 h-4" />
                      </Button>
                      <button 
                        @click="selectedMembers = selectedMembers.filter(m => m.id !== member.id)"
                        class="text-gray-500 hover:text-gray-700"
                      >
                        <X class="w-4 h-4" />
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Available Members -->
              <div class="space-y-2 max-h-48 overflow-y-auto">
                <div v-for="user in filteredAvailableUsers" :key="user.id" 
                  class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded cursor-pointer"
                  @click="selectedMembers.push(user)"
                >
                  <input 
                    type="checkbox" 
                    :checked="selectedMembers.some(m => m.id === user.id)"
                    class="w-4 h-4"
                    @click.stop
                  />
                  <img :src="user.avatar" :alt="user.name" class="w-10 h-10 rounded-full" />
                  <div>
                    <div class="font-medium">{{ user.name }}</div>
                    <div class="text-sm text-gray-500">{{ user.role }}</div>
                  </div>
                </div>
                <div v-if="filteredAvailableUsers.length === 0" class="text-center text-gray-500 py-4">
                  No members found
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-2">
              <Button variant="outline" @click="showCreateModal = false">Cancel</Button>
              <Button 
                @click="createGroupChat" 
                :disabled="!newGroupName || selectedMembers.length === 0"
              >
                Create Group
              </Button>
            </div>
          </div>
        </DialogContent>
      </Dialog>

      <!-- Members Modal -->
      <Dialog v-model:open="showMembersModal">
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Group Members</DialogTitle>
            <DialogDescription>Manage group members and their roles.</DialogDescription>
          </DialogHeader>
          <div class="mt-4">
            <div class="flex justify-between items-center mb-4">
              <h3 class="font-medium">Members ({{ selectedChat?.participants.length }})</h3>
              <div class="flex gap-2">
                <Button size="sm" variant="outline" @click="showAddMembersModal = true">
                  <Plus class="w-4 h-4 mr-1" /> Add Members
                </Button>
              </div>
            </div>
            
            <!-- Administrators Section -->
            <div class="mb-4">
              <h4 class="text-sm font-medium text-gray-500 mb-2">Administrators</h4>
              <div class="space-y-2">
                <div v-for="admin in selectedChat?.administrators" :key="admin" 
                  class="flex items-center justify-between p-2 bg-blue-50 rounded">
                  <div class="flex items-center gap-2">
                    <span>{{ admin }}</span>
                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">Admin</span>
                  </div>
                  <Button 
                    size="sm" 
                    variant="outline" 
                    @click="demoteAdmin(admin)" 
                    class="text-gray-600 hover:text-gray-800"
                    :disabled="selectedChat?.administrators?.length <= 1"
                  >
                    Demote
                  </Button>
                </div>
              </div>
            </div>

            <!-- Regular Members Section -->
            <div>
              <h4 class="text-sm font-medium text-gray-500 mb-2">Members</h4>
              <div class="space-y-2">
                <div v-for="member in selectedChat?.participants.filter(p => !selectedChat?.administrators.includes(p))" 
                  :key="member" 
                  class="flex items-center justify-between p-2 bg-gray-50 rounded">
                  <span>{{ member }}</span>
                  <div class="flex gap-2">
                    <Button 
                      size="sm" 
                      variant="outline" 
                      @click="promoteToAdmin(member)"
                      class="text-blue-600 hover:text-blue-800"
                    >
                      Promote
                    </Button>
                    <Button 
                      size="sm" 
                      variant="outline" 
                      @click="confirmKickMember(member)" 
                      class="text-red-500"
                    >
                      <X class="w-4 h-4" />
                    </Button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </DialogContent>
      </Dialog>

      <!-- Confirm Kick Modal -->
      <Dialog v-model:open="showConfirmKickModal">
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Remove Member</DialogTitle>
            <DialogDescription>Are you sure you want to remove {{ memberToKick }} from the group?</DialogDescription>
          </DialogHeader>
          <div class="mt-4 flex justify-end gap-2">
            <Button variant="outline" @click="showConfirmKickModal = false">Cancel</Button>
            <Button variant="destructive" @click="kickMember">Remove</Button>
          </div>
        </DialogContent>
      </Dialog>

      <!-- Add Members Modal -->
      <Dialog v-model:open="showAddMembersModal">
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Add Members</DialogTitle>
            <DialogDescription>Search and select members to add to the group chat.</DialogDescription>
          </DialogHeader>
          <div class="mt-4">
            <div class="mb-4">
              <input 
                v-model="searchMember" 
                type="text" 
                placeholder="Search members..." 
                class="border rounded px-3 py-2 text-sm w-full"
              />
            </div>
            
            <!-- Selected Members -->
            <div v-if="selectedMembers.length > 0" class="mb-4">
              <h3 class="text-sm font-medium mb-2">Selected Members</h3>
              <div class="flex flex-wrap gap-2">
                <div v-for="member in selectedMembers" :key="member.id" 
                  class="flex items-center gap-2 bg-blue-50 rounded-full px-3 py-1">
                  <img :src="member.avatar" :alt="member.name" class="w-6 h-6 rounded-full" />
                  <span class="text-sm">{{ member.name }}</span>
                  <button @click="removeSelectedMember(member)" class="text-gray-500 hover:text-gray-700">
                    <X class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>

            <!-- Suggested Members -->
            <div class="space-y-2">
              <h3 class="text-sm font-medium mb-2">Suggested Members</h3>
              <div v-for="user in filteredAvailableUsers" :key="user.id" 
                class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded cursor-pointer"
                @click="selectedMembers.push(user)">
                <input 
                  type="checkbox" 
                  :checked="selectedMembers.some(m => m.id === user.id)"
                  class="w-4 h-4"
                  @click.stop
                />
                <img :src="user.avatar" :alt="user.name" class="w-10 h-10 rounded-full" />
                <div>
                  <div class="font-medium">{{ user.name }}</div>
                  <div class="text-sm text-gray-500">{{ user.role }}</div>
                </div>
              </div>
              <div v-if="filteredAvailableUsers.length === 0" class="text-center text-gray-500 py-4">
                No members found.
              </div>
            </div>

            <div class="mt-4 flex justify-end gap-2">
              <Button variant="outline" @click="showAddMembersModal = false">Cancel</Button>
              <Button @click="addSelectedMembers" :disabled="selectedMembers.length === 0">
                Add Selected Members
              </Button>
            </div>
          </div>
        </DialogContent>
      </Dialog>

      <!-- Group Settings Modal -->
      <Dialog v-model:open="showGroupSettingsModal">
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Group Settings</DialogTitle>
            <DialogDescription>Update group information and settings.</DialogDescription>
          </DialogHeader>
          <div class="mt-4 space-y-4">
            <div>
              <label class="block text-sm font-medium mb-1">Group Name</label>
              <div class="flex gap-2">
                <input 
                  ref="groupNameInput"
                  v-model="newGroupName" 
                  type="text" 
                  class="border rounded-lg px-3 py-2.5 text-sm w-full focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" 
                  autocomplete="off"
                  tabindex="0"
                />
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium mb-2">Group Color</label>
              <div class="space-y-3">
                <!-- Predefined Colors -->
                <div class="flex flex-wrap gap-2">
                  <button
                    v-for="color in predefinedColors"
                    :key="color.value"
                    @click="() => { selectedColor = color.value; isCustomColor = false; }"
                    :class="[
                      'w-8 h-8 rounded-full border-2 transition-all',
                      color.class,
                      selectedColor === color.value ? 'ring-2 ring-offset-2 ring-primary scale-110' : 'hover:scale-110',
                      isCustomColor ? 'opacity-50' : ''
                    ]"
                    :title="color.name"
                  />
                  <!-- Custom Color Button -->
                  <button
                    @click="() => { isCustomColor = true; selectedColor = ''; }"
                    :class="[
                      'w-8 h-8 rounded-full border-2 border-dashed border-gray-300 flex items-center justify-center transition-all',
                      isCustomColor ? 'ring-2 ring-offset-2 ring-primary scale-110 bg-gray-50' : 'hover:bg-gray-50',
                      selectedColor ? 'opacity-50' : ''
                    ]"
                    title="Custom Color"
                  >
                    <Plus class="w-4 h-4 text-gray-500" />
                  </button>
                </div>

                <!-- Custom Color Picker (shown when + is clicked) -->
                <div v-if="isCustomColor" class="mt-2 p-3 border rounded-lg bg-gray-50">
                  <div class="flex items-center gap-3">
                    <input
                      v-model="customColor"
                      type="color"
                      class="w-8 h-8 rounded-full cursor-pointer border-2 border-gray-200"
                    />
                    <div class="flex-1">
                      <div class="text-sm font-medium mb-1">Custom Color</div>
                      <div class="text-sm text-gray-500">Select a custom color for this group</div>
                    </div>
                    <Button 
                      size="sm" 
                      variant="ghost" 
                      @click="() => { isCustomColor = false; customColor = '#3B82F6'; }"
                    >
                      <X class="w-4 h-4" />
                    </Button>
                  </div>
                </div>

                <!-- Color Preview -->
                <div v-if="selectedColor || (isCustomColor && customColor)" class="mt-2 p-2 rounded border">
                  <div class="text-sm font-medium mb-1">Preview</div>
                  <div 
                    class="h-8 rounded border-l-4 transition-all"
                    :class="[
                      isCustomColor 
                        ? calculateGradient(customColor).border 
                        : predefinedColors.find(c => c.value === selectedColor)?.border
                    ]"
                    :style="{
                      background: isCustomColor 
                        ? `linear-gradient(to right, ${calculateGradient(customColor).gradient})`
                        : `linear-gradient(to right, ${predefinedColors.find(c => c.value === selectedColor)?.gradient})`
                    }"
                  ></div>
                </div>
              </div>
            </div>

            <div class="flex justify-end gap-2">
              <Button variant="outline" @click="showGroupSettingsModal = false">Cancel</Button>
              <Button @click="updateGroupSettings">Save Changes</Button>
            </div>
          </div>
        </DialogContent>
      </Dialog>

      <!-- Documents Modal -->
      <Dialog v-model:open="showDocumentsModal">
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Group Documents</DialogTitle>
            <DialogDescription>View and manage shared documents in this group.</DialogDescription>
          </DialogHeader>
          <div class="mt-4">
            <!-- Search input -->
            <div class="relative mb-4">
              <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
              <input 
                v-model="documentSearch" 
                type="text" 
                placeholder="Search documents..." 
                class="w-full pl-10 pr-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
              />
            </div>

            <div class="space-y-2">
              <!-- Show filtered documents -->
              <div v-for="doc in filteredDocuments" :key="doc.name" 
                class="flex items-center justify-between p-2 bg-gray-50 rounded hover:bg-gray-100 transition-colors">
                <div class="flex items-center gap-2">
                  <FileText class="w-4 h-4 text-gray-500" />
                  <div>
                    <div class="font-medium">{{ doc.name }}</div>
                    <div class="text-xs text-gray-500">{{ doc.size }} • {{ doc.date }}</div>
                  </div>
                </div>
                <div class="flex items-center gap-2">
                  <Button 
                    size="sm" 
                    variant="outline" 
                    class="text-gray-600 hover:text-gray-800"
                    title="Preview Document"
                  >
                    <Eye class="w-4 h-4" />
                  </Button>
                  <Button 
                    size="sm" 
                    variant="outline"
                    title="Download Document"
                  >
                    Download
                  </Button>
                </div>
              </div>

              <!-- No results message -->
              <div v-if="documentSearch && filteredDocuments.length === 0" 
                class="text-center text-gray-500 py-4">
                No documents found matching "{{ documentSearch }}"
              </div>

              <!-- No documents message -->
              <div v-if="!documentSearch && !selectedChat?.documents.length" 
                class="text-center text-gray-500 py-4">
                No documents shared yet.
              </div>

              <!-- Start searching message -->
              <div v-if="!documentSearch && selectedChat?.documents.length" 
                class="text-center text-gray-500 py-4">
                Start typing to search documents
              </div>
            </div>
          </div>
        </DialogContent>
      </Dialog>

      <!-- Search Messages Modal -->
      <Dialog v-model:open="showSearchModal">
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Search Messages</DialogTitle>
            <DialogDescription>Search through messages in this chat.</DialogDescription>
          </DialogHeader>
          <div class="mt-4">
            <div class="relative mb-4">
              <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
              <input 
                v-model="messageSearch" 
                type="text" 
                placeholder="Search messages..." 
                class="w-full pl-10 pr-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
              />
            </div>
            
            <div class="space-y-4 max-h-[400px] overflow-y-auto">
              <div v-for="(message, index) in searchResults" :key="index" class="flex gap-3 p-2 hover:bg-gray-50 rounded-lg">
                <img :src="message.avatar" :alt="message.sender" class="w-8 h-8 rounded-full" />
                <div class="flex-1">
                  <div class="flex items-center gap-2">
                    <span class="font-medium">{{ message.sender }}</span>
                    <span class="text-xs text-gray-500">{{ message.time }}</span>
                  </div>
                  <p class="text-sm">{{ message.text }}</p>
                </div>
              </div>
              <div v-if="messageSearch && searchResults.length === 0" class="text-center text-gray-500 py-4">
                No messages found matching "{{ messageSearch }}"
              </div>
              <div v-if="!messageSearch" class="text-center text-gray-500 py-4">
                Start typing to search messages
              </div>
            </div>
          </div>
        </DialogContent>
      </Dialog>

      <!-- Leave Group Confirmation Modal -->
      <Dialog v-model:open="showLeaveGroupModal">
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Leave Group</DialogTitle>
            <DialogDescription>
              Are you sure you want to leave "{{ groupToLeave?.name }}"? You will no longer have access to this group's messages and documents.
            </DialogDescription>
          </DialogHeader>
          
          <div class="mt-4 flex justify-end gap-2">
            <Button variant="outline" @click="showLeaveGroupModal = false">Cancel</Button>
            <Button variant="destructive" @click="confirmLeaveGroup">Leave Group</Button>
          </div>
        </DialogContent>
      </Dialog>
    </div>
  </AppLayout>
</template> 