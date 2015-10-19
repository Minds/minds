import { Type } from 'angular2/angular2';
import { SubscribeButton } from './buttons/subscribe';
import { UserDropdownButton } from './buttons/user-dropdown';
import { ThumbsUpButton } from './buttons/thumbs-up';
import { ThumbsDownButton } from './buttons/thumbs-down';
import { CommentButton } from './buttons/comment';
import { RemindButton } from './buttons/remind';
import { FeatureButton } from './buttons/feature';

export const BUTTON_COMPONENTS: Type[] = [SubscribeButton, UserDropdownButton, ThumbsUpButton, ThumbsDownButton, CommentButton, RemindButton, FeatureButton];
