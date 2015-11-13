import { MindsMessage } from './entities';
import { MindsResponse } from '../../../interfaces/responses';

export interface MindsMessageResponse extends MindsResponse{
  message : MindsMessage
}

export interface MindsConversationResponse extends MindsResponse {
  conversations : Array<any>,
  'load-next' : string
}

export interface MindsUserConversationResponse extends MindsResponse{
  publickeys : any,
  messages : Array<MindsMessage>,
  'load-previous' : string,
  'load-next' : string
}

export interface MindsKeysResponse extends MindsResponse{
  key ?: string
  password ?: string
}
