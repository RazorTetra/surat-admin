// resources/js/types/letter.ts

export interface User {
    id: number;
    name: string;
    email: string;
    created_at: string;
    updated_at: string;
}

export interface IncomingLetter {
    id: number;
    reference_number: string;
    origin: string;
    sender: string;
    recipient: string;
    subject: string;
    description?: string;
    letter_date: string;
    received_date: string;
    type: 'official' | 'personal' | 'confidential';
    priority: 'high' | 'medium' | 'low';
    status: 'received' | 'processed' | 'completed';
    notes?: string;
    received_by: number;
    processed_by?: number;
    receivedBy: User;
    processedBy?: User;
    attachments: LetterAttachment[];
    tracking: LetterTracking[];
    created_at: string;
    updated_at: string;
    deleted_at?: string;
}

export interface OutgoingLetter {
    id: number;
    reference_number: string;
    recipient: string;
    recipient_address: string;
    subject: string;
    content: string;
    letter_date: string;
    type: 'official' | 'personal' | 'confidential';
    priority: 'high' | 'medium' | 'low';
    status: 'draft' | 'review' | 'approved' | 'sent';
    notes?: string;
    created_by: number;
    approved_by?: number;
    sent_at?: string;
    createdBy: User;
    approvedBy?: User;
    attachments: LetterAttachment[];
    tracking: LetterTracking[];
    created_at: string;
    updated_at: string;
    deleted_at?: string;
}

export interface LetterTemplate {
    id: number;
    name: string;
    code: string;
    content: string;
    variables?: Record<string, string>;
    description?: string;
    type: 'incoming' | 'outgoing';
    is_active: boolean;
    created_by: number;
    updated_by?: number;
    createdBy: User;
    updatedBy?: User;
    created_at: string;
    updated_at: string;
    deleted_at?: string;
}

export interface LetterAttachment {
    id: number;
    letterable_type: string;
    letterable_id: number;
    filename: string;
    original_filename: string;
    file_path: string;
    mime_type: string;
    file_size: number;
    description?: string;
    uploaded_by: number;
    uploadedBy: User;
    created_at: string;
    updated_at: string;
    deleted_at?: string;
}

export interface LetterTracking {
    id: number;
    trackable_type: string;
    trackable_id: number;
    status: string;
    description?: string;
    metadata?: Record<string, any>;
    user_id: number;
    user: User;
    created_at: string;
    updated_at: string;
}