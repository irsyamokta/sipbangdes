import React, { createContext, useContext, useState, useEffect } from "react";

export type DebugLog = {
  id: string;
  type: "info" | "error" | "api" | "validation";
  title: string;
  data?: any;
  timestamp: number;
};

type DebugContextType = {
  logs: DebugLog[];
  addLog: (log: Omit<DebugLog, "id" | "timestamp">) => void;
  clearLogs: () => void;
};

const DebugContext = createContext<DebugContextType | null>(null);

export const DebugProvider = ({ children }: { children: React.ReactNode }) => {
  const [logs, setLogs] = useState<DebugLog[]>([]);

  const addLog = (log: Omit<DebugLog, "id" | "timestamp">) => {
    setLogs((prev) => [
      {
        ...log,
        id: crypto.randomUUID(),
        timestamp: Date.now(),
      },
      ...prev,
    ]);
  };

  const clearLogs = () => setLogs([]);

  useEffect(() => {
    const originalLog = console.log;
    const originalError = console.error;

    console.log = (...args) => {
      addLog({
        type: "info",
        title: "console.log",
        data: args,
      });
      originalLog(...args);
    };

    console.error = (...args) => {
      addLog({
        type: "error",
        title: "console.error",
        data: args,
      });
      originalError(...args);
    };

    window.onerror = (message, source, lineno, colno, error) => {
      addLog({
        type: "error",
        title: "Runtime Error",
        data: { message, source, lineno, colno, error },
      });
    };

    window.onunhandledrejection = (event) => {
      addLog({
        type: "error",
        title: "Promise Error",
        data: event.reason,
      });
    };
  }, []);

  return (
    <DebugContext.Provider value={{ logs, addLog, clearLogs }}>
      {children}
    </DebugContext.Provider>
  );
};

export const useDebug = () => {
  const ctx = useContext(DebugContext);
  if (!ctx) throw new Error("useDebug must be used within DebugProvider");
  return ctx;
};