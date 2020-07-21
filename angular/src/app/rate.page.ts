import { Rate } from './rate';

export interface RatePage {
    currentPage: number;
    totalPages: string;
    totalItems: string;
    items: Rate[];
}
