import { Type } from 'angular2/angular2';
import { UserCard } from './user';
import { VideoCard } from './object/video';
import { ImageCard } from './object/image';
import { AlbumCard } from './object/album';
import { Activity } from './activity';
import { CommentCard } from './comment';

export { UserCard } from './user';
export { VideoCard } from './object/video';
export { ImageCard } from './object/image';
export { AlbumCard } from './object/album';
export const CARDS: Type[] = [ UserCard, VideoCard, ImageCard, AlbumCard, Activity, CommentCard ];
