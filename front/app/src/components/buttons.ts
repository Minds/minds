import { Type } from 'angular2/angular2';
import { SubscribeButton } from './buttons/subscribe';
import { ThumbsUpButton } from './buttons/thumbs-up';
import { ThumbsDownButton } from './buttons/thumbs-down';
import { CommentButton } from './buttons/comment';
import { RemindButton } from './buttons/remind';

export const BUTTON_COMPONENTS: Type[] = [SubscribeButton, ThumbsUpButton, ThumbsDownButton, CommentButton, RemindButton];
