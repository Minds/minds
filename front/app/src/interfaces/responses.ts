import { MindsUser } from 'src/interfaces/entities';
import { MindsGroup } from 'src/interfaces/entities';
/*
* Minds response object
*/
export interface MindsResponse {}

export interface MindsChannelResponse extends MindsResponse {
  status : string,
  message : string,
  channel : MindsUser
}

export interface MindsBlogResponse extends MindsResponse {
  blog : any
}

export interface MindsBlogListResponse extends MindsResponse {
  blogs : Array<any>,
  'load-next' : string
}

export interface MindsConversationResponse extends MindsResponse {
  conversations : Array<any>,
  'load-next' : string
}

export interface MindsUserConversationResponse extends MindsResponse{
  publickeys : any,
  messages : Array<any>,
  'load-previous' : string,
  'load-next' : string
}

export interface MindsGatheringsSearchResponse extends MindsResponse{
  user : Array<any>
}
export interface MindsKeysResponse extends MindsResponse{
  key : any
}

export interface MindsGroupResponse extends MindsResponse{
  group : MindsGroup
}

export interface MindsGroupListResponse extends MindsResponse {
  groups : Array<any>,
  'load-next' : string
}
