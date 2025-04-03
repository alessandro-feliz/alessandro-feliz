%%
% Runs the 'Rest' scenario using the 3 methods.
%%
function rest(duration, variation)

  % Run the 'Rest' scenario with the estimation method
  heart_rate = estimation_method(duration, variation);

  % Run the 'Rest' scenario with the Fick's based method
  fick_method(duration, variation);

  % Run the 'Rest' scenario with the cardiac output principle method
  principle_method(duration, variation, heart_rate);

end

function heart_rate = estimation_method(duration, variation)
  % Rest parameters
  min_rate = 75;       % Minimum heart rate
  max_rate = 85;       % Maximum heart rate
  min_systolic = 100;   % Minimum systolic blood pressure
  max_systolic = 110;  % Maximum systolic blood pressure
  min_diastolic = 75;  % Minimum diastolic blood pressure
  max_diastolic = 85;  % Maximum diastolic blood pressure

  % Simulate Heart Rate
  heart_rate = simulate_heart_rate(duration, min_rate, max_rate, variation);
  % Simulate Blood Pressure
  [systolic_pressure, diastolic_pressure] = simulate_blood_pressure(duration, min_systolic, max_systolic, min_diastolic, max_diastolic, variation);
  % Simulate Cardiac Output
  cardiac_output = estimation(heart_rate, systolic_pressure, diastolic_pressure);

  % Plotting
  time = linspace(0, duration, duration);
  f = figure('name', 'Rest Scenario - Estimation Method');
  set(f, 'Position', [0 0 1400 400]);
  grid on;
  subplot(1,3,1);
  plot(time, heart_rate, 'r');
  title('Simulated Heart Rate Signal Over Time');
  xlabel('Time (seconds)');
  ylabel('Heart Rate (bpm)');

  subplot(1,3,2);
  plot(time, systolic_pressure, 'r', time, diastolic_pressure, 'b');
  title('Simulated Blood Pressure Signal Over Time');
  xlabel('Time (seconds)');
  ylabel('Blood Pressure (mmHg)');
  legend('Systolic', 'Diastolic');

  subplot(1,3,3);
  plot(time, cardiac_output, 'm');
  % plot the average line
  average_cardiac_output = mean(cardiac_output);
  average_line = ones(size(time)) * average_cardiac_output;
  hold on;
  plot(time, average_line, 'r--', 'LineWidth', 1);
  text(time(end), average_cardiac_output + 10, num2str(int32(average_cardiac_output)), 'VerticalAlignment', 'bottom', 'HorizontalAlignment', 'right', 'FontSize', 14, 'Color', 'black');
  hold off;
  title('Cardiac Output Over Time');
  xlabel('Time (seconds)');
  ylabel('Cardiac Output (mL/min)');
end

function fick_method(duration, variation)
  % Rest parameters
  avg_o2_consumption = 225;             % Average O2 consumption
  avg_o2_arterial_concentration = 20;   % Average O2 arterial concentration
  avg_o2_venous_concentration = 15;     % Average O2 venous concentration

  % Simulate O2 consumption
  o2_consumption = simulate_oxygen_consume(duration, avg_o2_consumption, variation);
  % Simulate O2 concentration in arterial blood
  o2_arterial_concentration = simulate_oxygen_blood_concentration(duration, avg_o2_arterial_concentration, variation);
  % Simulate O2 concentration in venous blood
  o2_venous_concentration = simulate_oxygen_blood_concentration(duration, avg_o2_venous_concentration, variation);
  % Simulate Cardiac Output
  cardiac_output = fick(o2_consumption, o2_arterial_concentration, o2_venous_concentration);

  % Plotting
  time = linspace(0, duration, duration);
  f = figure('name', 'Rest Scenario - Fick Method');
  set(f, 'Position', [0 0 1400 400]);
  grid on;
  subplot(1,3,1);
  plot(time, o2_consumption, 'c');
  title('Simulated Oxygen Consumption Signal Over Time');
  xlabel('Time (seconds)');
  ylabel('Oxygen Consumption (mL/min)');

  subplot(1,3,2);
  plot(time, o2_arterial_concentration, 'r', time, o2_venous_concentration, 'b');
  title('Simulated O2 concentration Signal Over Time');
  xlabel('Time (seconds)');
  ylabel('O2 Concentration (mL/mL)');
  legend('Arterial', 'Venous');

  subplot(1,3,3);
  plot(time, cardiac_output, 'm');
  % plot the average line
  average_cardiac_output = mean(cardiac_output);
  average_line = ones(size(time)) * average_cardiac_output;
  hold on;
  plot(time, average_line, 'r--', 'LineWidth', 1);
  text(time(end), average_cardiac_output + 10, num2str(int32(average_cardiac_output)), 'VerticalAlignment', 'bottom', 'HorizontalAlignment', 'right', 'FontSize', 14, 'Color', 'black');
  hold off;
  title('Cardiac Output Over Time');
  xlabel('Time (seconds)');
  ylabel('Cardiac Output (mL/min)');
end

function principle_method(duration, variation, heart_rate)
  % Rest parameters
  avg_stroke_volume = 70;   % Average stroke volume

  % Simulate Stroke Volume
  stroke_volume = simulate_stroke_volume(duration, avg_stroke_volume, variation);
  % Simulate Cardiac Output
  cardiac_output = principle(heart_rate, stroke_volume);

  % Plotting
  time = linspace(0, duration, duration);
  f = figure('name', 'Rest Scenario - Principle Method');
  set(f, 'Position', [0 0 1400 400]);
  grid on;
  subplot(1,3,1);
  plot(time, heart_rate, 'r');
  title('Simulated Heart Rate Signal Over Time');
  xlabel('Time (seconds)');
  ylabel('Heart Rate (bpm)');

  subplot(1,3,2);
  plot(time, stroke_volume);
  title('Simulated Stroke Volume Signal Over Time');
  xlabel('Time (seconds)');
  ylabel('Stroke Volume (mL)');

  subplot(1,3,3);
  plot(time, cardiac_output, 'm');
  % plot the average line
  average_cardiac_output = mean(cardiac_output);
  average_line = ones(size(time)) * average_cardiac_output;
  hold on;
  plot(time, average_line, 'r--', 'LineWidth', 1);
  text(time(end), average_cardiac_output + 10, num2str(int32(average_cardiac_output)), 'VerticalAlignment', 'bottom', 'HorizontalAlignment', 'right', 'FontSize', 14, 'Color', 'black');
  hold off;
  title('Cardiac Output Over Time');
  xlabel('Time (seconds)');
  ylabel('Cardiac Output (mL/min)');
end
