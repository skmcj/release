export interface SelectItem {
  value: string;
  label: string;
  type: string;
  iconClass?: string;
}

export interface WorkLabel {
  icon?: string;
  tip?: string;
  link?: string;
  color?: string;
}

export interface WorkItem {
  logo?: string;
  name?: string;
  tip?: string;
  date?: string;
  labels?: WorkLabel[];
}
