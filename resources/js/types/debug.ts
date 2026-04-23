export type DebugLog = {
  id: string;
  type: "info" | "error" | "api" | "validation";
  title: string;
  data?: any;
  timestamp: number;
};